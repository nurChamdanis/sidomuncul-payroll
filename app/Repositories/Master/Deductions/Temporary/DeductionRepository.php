<?php

namespace App\Repositories\Master\Deductions\Temporary;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\BaseRepository;
use Exception;

class DeductionRepository extends BaseRepository{
    protected $table = 'tb_t_payroll_deductions';
    protected $tb_m_company = 'tb_m_company';
    protected $tb_m_work_unit = 'tb_m_work_unit';
    protected $tb_m_system = 'tb_m_system_payroll';
    protected $tb_m_glaccount = 'tb_m_payroll_gl';
    protected $modifyTableAndCondition = true;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return string $compiledselect
     * ----------------------------------------------------
     * name: newTableAliases()
     * desc: Retrieving subquery for new aliases table
     */
    public function setCustomTable()
    {
        $query = $this->db->table($this->table);
        $query->select("
            {$this->table}.*,
            {$this->tb_m_glaccount}.gl_code,
            {$this->tb_m_glaccount}.gl_name,
            CALCULATIONTYPE.system_value_txt as calculation_type_name,
            CALCULATIONMODE.system_value_txt as calculation_mode_name,
        ");
        $query->join($this->tb_m_company, "{$this->tb_m_company}.company_id = {$this->table}.company_id", "left");
        $query->join($this->tb_m_glaccount, "{$this->tb_m_glaccount}.gl_id = {$this->table}.gl_id", "left");
        $query->join($this->tb_m_system . " CALCULATIONTYPE", "CALCULATIONTYPE.system_code = {$this->table}.calculation_type AND CALCULATIONTYPE.system_type = 'calculation_type'", "left");
        $query->join($this->tb_m_system . " CALCULATIONMODE", "CALCULATIONMODE.system_code = {$this->table}.calculation_mode AND CALCULATIONMODE.system_type = 'calculation_mode'", "left");
        
        $this->customTable = "({$query->getCompiledSelect()}) temporary_master_deductions";
    }

    public function importExcel($process_id, $company_id = '')
    {
        $sql = "INSERT INTO tb_m_payroll_deductions (
                    company_id,
                    deduction_code,
                    deduction_name,
                    default_value,
                    calculation_type,
                    calculation_mode,
                    gl_id,
                    effective_date,
                    effective_date_end,
                    is_active,
                    created_by,
                    created_dt,
                    changed_by,
                    changed_dt,
                    process_id
                )
                SELECT
                    company_id,
                    deduction_code,
                    deduction_name,
                    default_value,
                    calculation_type,
                    calculation_mode,
                    gl_id,
                    effective_date,
                    effective_date_end,
                    '1',
                    created_by,
                    created_dt,
                    changed_by,
                    changed_dt,
                    CONCAT(process_id,transaction_id) as process_id
                FROM 
                    tb_t_payroll_deductions
                WHERE (1=1)
                AND process_id = '{$process_id}'
                AND valid_flg = '1'
            ";

        $executeInsertInto = $this->db->query($sql . " AND update_flg = '0'");

        if(!$executeInsertInto) throw new Exception('Failed execute importExcel: line 92');

        $updateTableData = $this->db->table($this->table);
        $updateTableData->where(array('valid_flg' => '1', 'update_flg' => '1', 'process_id' => $process_id));
        $updateData = $updateTableData->get()->getResult();
        
        if(!empty($updateData)){
            $insertNewRow = array();
            $updateRow = array();
            $deduction_code = array_map(function($item){
                return $item->deduction_code;
            },$updateData);  

            $currentTableData = $this->db->table('tb_m_payroll_deductions');
            $currentTableData->whereIn('deduction_code', $deduction_code);
            $currentData = $currentTableData->get()->getResult();

            $comparedData = array_map(function($item){
                return [
                    'company_id' => $item->company_id,
                    'deduction_code' => $item->deduction_code,
                    'deduction_name' => $item->deduction_name,
                    'default_value' => $item->default_value,
                    'effective_date' => $item->effective_date,
                    'is_active' => $item->is_active,
                ];
            },$currentData);  
            
            $x = 0;
            $y = 0;
            foreach ($updateData as $key => $value) {
                $filteredData = array_values(array_filter($comparedData, function($item) use ($value, $company_id) {
                    return $item['company_id'] == $company_id && $item['deduction_code'] == $value->deduction_code && $item['is_active'] == 1;
                }));

                if(isset($filteredData[0]))
                {
                    $deductionBefore = $filteredData[0];
            
                    if(
                        $deductionBefore['default_value'] != $value->default_value || 
                        $deductionBefore['effective_date'] != $value->effective_date  
                    )
                    {
                        $insertNewRow[$x] = $value->deduction_code;
                        $x++;
                    }
                    else
                    {
                        $updateRow[$y] = $value->deduction_code;
                        $y++;
                    }
                } 
            }

            // If have change data then insert new update before
            if(!empty($insertNewRow)){
                $whereIn = implode(',', array_map(function ($code) {
                    return $this->db->escape($code);
                }, $insertNewRow));
                $this->updateInsertPayrollDeductions($process_id, $insertNewRow, $company_id);

                // Insert New Values
                $sql .= " AND deduction_code IN ({$whereIn})";
                $this->db->query($sql . " AND update_flg = '1'");
            }

            if(!empty($updateRow)){
                $this->updatePayrollDeductions($process_id, $updateRow, $company_id);
            }
        }
        
        return true;
    }

    public function updateInsertPayrollDeductions($process_id, $deduction_codes,  $company_id = '', $is_active = '0')
    {
        $whereIn = implode(',', array_map(function ($code) {
            return $this->db->escape($code);
        }, $deduction_codes));
        
        // Drop the temporary tables
        $sqlDropTempTables = "
            DROP TEMPORARY TABLE IF EXISTS deduction_latest_effective_dates;
            DROP TEMPORARY TABLE IF EXISTS deduction_new_effective_dates;
        ";
        $executeSqlDropTempTables = $this->db->query($sqlDropTempTables);
        
        // echo $sqlDropTempTables;
        // echo '<br/>';
        // exit();

        // Step 1: Create a derived table with the latest effective dates for each deduction_code
        $sqlStep1 = "
            CREATE TEMPORARY TABLE deduction_latest_effective_dates AS SELECT
            process_id,
            transaction_id,
            deduction_code,
            effective_date
            FROM
                tb_t_payroll_deductions 
            WHERE
                process_id = '{$process_id}' 
                AND deduction_code IN ( {$whereIn} );
        ";
        $executeSqlStep1 = $this->db->query($sqlStep1, [$process_id]);
        
        if(!$executeSqlStep1) throw new Exception('Failed execute importExcel: line 199');

        // echo $sqlStep1;
        // echo '<br/>';

        // Step 2: Join the derived table with tb_t_payroll_deductions to get the new_effective_date_end
        $sqlStep2 = "
            CREATE TEMPORARY TABLE deduction_new_effective_dates AS SELECT
            t1.deduction_code,
            DATE_SUB( t1.effective_date, INTERVAL 1 DAY ) AS new_effective_date_end,
            t1.process_id,
            t1.transaction_id
            FROM
                tb_t_payroll_deductions t1
                JOIN deduction_latest_effective_dates t2 ON t1.deduction_code = t2.deduction_code 
                AND t1.effective_date = t2.effective_date AND t1.process_id = '{$process_id}';
        ";
        $executeSqlStep2 = $this->db->query($sqlStep2);
        
        if(!$executeSqlStep2) throw new Exception('Failed execute importExcel: line 218');
        // echo $sqlStep2;
        // echo '<br/>';

        // Step 3: Update tb_m_payroll_deductions using the new effective dates
        $sqlStep3 = "
            UPDATE tb_m_payroll_deductions t1
            JOIN deduction_new_effective_dates t2 ON t1.deduction_code = t2.deduction_code 
            SET t1.effective_date_end = t2.new_effective_date_end,
            t1.is_active = '{$is_active}',
            t1.process_id = CONCAT( '{$process_id}', t2.transaction_id ) 
            WHERE
                t1.deduction_id IN (
                SELECT
                    MAX( deduction_id ) 
                FROM
                    tb_m_payroll_deductions 
                WHERE
                    deduction_code IN ( {$whereIn} ) 
                    AND effective_date_end = '2999-12-31' 
                    AND company_id = '{$company_id}'
            GROUP BY
                deduction_id);
        ";
        $executeSqlStep3 = $this->db->query($sqlStep3);
        
        if(!$executeSqlStep3) throw new Exception('Failed execute importExcel: line 244');
        // echo $sqlStep3;
        // echo '<br/>';
    }

    public function updatePayrollDeductions($process_id, $deduction_codes, $company_id = '')
    {
        $whereIn = implode(',', array_map(function ($code) {
            return $this->db->escape($code);
        }, $deduction_codes));

        // Step 3: Update tb_m_payroll_deductions using the new effective dates
        $sql = "
            UPDATE tb_m_payroll_deductions t1
            JOIN tb_t_payroll_deductions t2
            ON t1.deduction_code = t2.deduction_code AND t1.is_active = '1'
            SET 
                t1.deduction_name = t2.deduction_name,
                t1.calculation_type = t2.calculation_type,
                t1.effective_date = t2.effective_date,
                t1.gl_id = t2.gl_id,
                t1.effective_date_end = t2.effective_date_end,
                t1.process_id = CONCAT(t2.process_id, t2.transaction_id)
            WHERE
                t1.deduction_code IN ({$whereIn})
            AND t2.process_id = '{$process_id}'
            AND t1.company_id = '{$company_id}'";

        $executeSqlUpdatePayrollDeduction = $this->db->query($sql);
        
        if(!$executeSqlUpdatePayrollDeduction) throw new Exception('Failed execute importExcel: line 272');
    }
}