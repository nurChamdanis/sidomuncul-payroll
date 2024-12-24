<?php

namespace App\Repositories\Master\Allowances\Temporary;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\BaseRepository;
use Exception;

class AllowanceRepository extends BaseRepository{
    protected $table = 'tb_t_payroll_allowances';
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
        
        $this->customTable = "({$query->getCompiledSelect()}) temporary_master_allowances";
    }

    public function importExcel($process_id, $company_id)
    {
        $sql = "INSERT INTO tb_m_payroll_allowances (
                    company_id,
                    allowance_code,
                    allowance_name,
                    default_value,
                    minimum_working_period,
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
                    allowance_code,
                    allowance_name,
                    default_value,
                    minimum_working_period,
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
                    tb_t_payroll_allowances
                WHERE (1=1)
                AND process_id = '{$process_id}'
                AND valid_flg = '1'
            ";
        $executeInsertInto = $this->db->query($sql . " AND update_flg = '0'");
        
        if(!$executeInsertInto) throw new Exception('Failed execute importExcel: line 93');

        $updateTableData = $this->db->table($this->table);
        $updateTableData->where(array('valid_flg' => '1', 'update_flg' => '1', 'process_id' => $process_id));
        $updateData = $updateTableData->get()->getResult();

        if(!empty($updateData)){
            $insertNewRow = array();
            $updateRow = array();
            $allowance_code = array_map(function($item){
                return $item->allowance_code;
            },$updateData);  

            $currentTableData = $this->db->table('tb_m_payroll_allowances');
            $currentTableData->whereIn('allowance_code', $allowance_code);
            $currentData = $currentTableData->get()->getResult();

            $comparedData = array_map(function($item){
                return [
                    'company_id' => $item->company_id,
                    'allowance_code' => $item->allowance_code,
                    'allowance_name' => $item->allowance_name,
                    'default_value' => $item->default_value,
                    'effective_date' => $item->effective_date,
                    'is_active' => $item->is_active,
                ];
            },$currentData);  
            
            $x = 0;
            $y = 0;
            foreach ($updateData as $key => $value) {
                $filteredData = array_values(array_filter($comparedData, function($item) use ($value,$company_id) {
                    return $item['company_id'] == $company_id && $item['allowance_code'] == $value->allowance_code && $item['is_active'] == 1;
                }));

                if(isset($filteredData[0]))
                {
                    $allowanceBefore = $filteredData[0];
            
                    if(
                        $allowanceBefore['default_value'] != $value->default_value || 
                        $allowanceBefore['effective_date'] != $value->effective_date  
                    )
                    {
                        $insertNewRow[$x] = $value->allowance_code;
                        $x++;
                    }
                    else 
                    {
                        $updateRow[$y] = $value->allowance_code;
                        $y++;
                    }
                } 
            }

            // If have change data then insert new update before
            if(!empty($insertNewRow)){
                $whereIn = implode(',', array_map(function ($code) {
                    return $this->db->escape($code);
                }, $insertNewRow));

                $this->updateInsertPayrollAllowances($process_id, $insertNewRow, $company_id);

                // Insert New Values
                $sql .= " AND allowance_code IN ({$whereIn})";
                $this->db->query($sql . " AND update_flg = '1'");
            }
            
            if(!empty($updateRow)){
                $this->updatePayrollAllowances($process_id, $updateRow, $company_id);
            }
        }
        
        return true;
    }

    public function updateInsertPayrollAllowances($process_id, $allowance_codes, $company_id = '', $is_active = '0')
    {
        $whereIn = implode(',', array_map(function ($code) {
            return $this->db->escape($code);
        }, $allowance_codes));

        // Step 1: Create a derived table with the latest effective dates for each allowance_code
        $sqlStep1 = "
            CREATE TEMPORARY TABLE allowance_latest_effective_dates AS SELECT
            process_id,
            transaction_id,
            allowance_code,
            effective_date
            FROM
                tb_t_payroll_allowances 
            WHERE
                process_id = '{$process_id}' 
                AND allowance_code IN ( {$whereIn} );
        ";
        $executeSqlStep1 = $this->db->query($sqlStep1, [$process_id]);
        
        if(!$executeSqlStep1) throw new Exception('Failed execute importExcel: line 187');

        // Step 2: Join the derived table with tb_t_payroll_allowances to get the new_effective_date_end
        $sqlStep2 = "
            CREATE TEMPORARY TABLE allowance_new_effective_dates AS SELECT
            t1.allowance_code,
            DATE_SUB( t1.effective_date, INTERVAL 1 DAY ) AS new_effective_date_end,
            t1.process_id,
            t1.transaction_id
            FROM
                tb_t_payroll_allowances t1
                JOIN allowance_latest_effective_dates t2 ON t1.allowance_code = t2.allowance_code 
                AND t1.effective_date = t2.effective_date AND t1.process_id = '{$process_id}';
        ";
        $executeSqlStep2 = $this->db->query($sqlStep2);
        
        if(!$executeSqlStep2) throw new Exception('Failed execute importExcel: line 206');

        // Step 3: Update tb_m_payroll_allowances using the new effective dates
        $sqlStep3 = "
            UPDATE tb_m_payroll_allowances t1
            JOIN allowance_new_effective_dates t2 ON t1.allowance_code = t2.allowance_code 
            SET t1.effective_date_end = t2.new_effective_date_end,
            t1.is_active = '{$is_active}',
            t1.process_id = CONCAT( '{$process_id}', t2.transaction_id ) 
            WHERE
                t1.allowance_id IN (
                SELECT
                    MAX( allowance_id ) 
                FROM
                    tb_m_payroll_allowances 
                WHERE
                    allowance_code IN ( {$whereIn} ) 
                    AND effective_date_end = '2999-12-31' 
                    AND company_id = '{$company_id}'
            GROUP BY
                allowance_id);
        ";
        $executeSqlStep3 = $this->db->query($sqlStep3);
        
        if(!$executeSqlStep3) throw new Exception('Failed execute importExcel: line 230');

        // Drop the temporary tables
        $sqlDropTempTables = "
            DROP TEMPORARY TABLE allowance_latest_effective_dates;
            DROP TEMPORARY TABLE allowance_new_effective_dates;
        ";
        
        $executeSqlDropTempTables = $this->db->query($sqlDropTempTables);
        
        if(!$executeSqlDropTempTables) throw new Exception('Failed execute importExcel: line 240');
    }

    public function updatePayrollAllowances($process_id, $allowance_codes, $company_id = '')
    {
        $whereIn = implode(',', array_map(function ($code) {
            return $this->db->escape($code);
        }, $allowance_codes));

        // Step 3: Update tb_m_payroll_allowances using the new effective dates
        $sql = "
            UPDATE tb_m_payroll_allowances t1
            JOIN tb_t_payroll_allowances t2
            ON t1.allowance_code = t2.allowance_code AND t1.is_active = '1'
            SET 
                t1.allowance_name = t2.allowance_name,
                t1.minimum_working_period = t2.minimum_working_period,
                t1.calculation_type = t2.calculation_type,
                t1.effective_date = t2.effective_date,
                t1.gl_id = t2.gl_id,
                t1.effective_date_end = t2.effective_date_end,
                t1.process_id = CONCAT(t2.process_id, t2.transaction_id)
            WHERE
                t1.allowance_code IN ({$whereIn})
            AND t2.process_id = '{$process_id}'
            AND t1.company_id = '{$company_id}'";

        $executeSqlUpdatePayrollDeduction = $this->db->query($sql);
        
        if(!$executeSqlUpdatePayrollDeduction) throw new Exception('Failed execute importExcel: line 269');
    }
}