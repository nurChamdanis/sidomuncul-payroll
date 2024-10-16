<?php

namespace App\Repositories\Master\Loan\Temporary;

/**
 * @author lutfi.hakim@arkamaya.co.id
 * @since 2024-05-28 
 * --------------------------------
 * @modified luthfi.hakim@arkamaya.co.id on May 2024
 */

use App\Repositories\BaseRepository;
use Exception;

class LoanRepository extends BaseRepository{
    protected $table = 'tb_t_payroll_loan';
    protected $tb_m_employee = 'tb_m_employee';
    protected $tb_m_company = 'tb_m_company';
    protected $tb_m_work_unit = 'tb_m_work_unit';
    protected $tb_m_system = 'tb_m_system_payroll';
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
            {$this->tb_m_employee}.employee_name,
            {$this->tb_m_employee}.role_id,
            {$this->tb_m_employee}.no_reg, 
            {$this->tb_m_company}.company_name, 
            {$this->tb_m_company}.company_code,
            {$this->tb_m_work_unit}.name as work_unit_name, 
            loan_type_system.system_value_txt as loan_type_name,
            loan_duration_system.system_value_txt as loan_duration_name
        ");
        $query->join($this->tb_m_employee, "{$this->tb_m_employee}.employee_id = {$this->table}.employee_id", "left");
        $query->join($this->tb_m_company, "{$this->tb_m_company}.company_id = {$this->table}.company_id", "left");
        $query->join($this->tb_m_work_unit, "{$this->tb_m_work_unit}.work_unit_id = {$this->table}.work_unit_id", "left");
        $query->join("{$this->tb_m_system} as loan_type_system", 
        "loan_type_system.system_code = {$this->table}.loan_type 
        AND loan_type_system.system_type = 'loan_type'
        ", "left");
        $query->join("{$this->tb_m_system} as loan_duration_system", 
        "loan_duration_system.system_code = {$this->table}.loan_duration 
        AND loan_duration_system.system_type = 'loan_duration'
        ", "left");
        
        $this->customTable = "({$query->getCompiledSelect()}) temporary_master_loan";
    }

    public function importExcel($process_id, $company_id)
    {
        $sql = "INSERT INTO tb_r_payroll_loan (
                    company_id,
                    work_unit_id,
                    employee_id,
                    loan_type,
                    loan_duration,
                    deduction_period_start,
                    deduction_period_end,
                    loan_total,
                    monthly_deduction,
                    remaining_loan,
                    loan_description,
                    loan_paid_off,
                    created_by,
                    created_dt,
                    changed_by,
                    changed_dt,
                    process_id
                )
                SELECT
                    company_id,
                    work_unit_id,
                    employee_id,
                    loan_type,
                    loan_duration,
                    deduction_period_start,
                    deduction_period_end,
                    loan_total,
                    monthly_deduction,
                    remaining_loan,
                    loan_description,
                    loan_paid_off,
                    created_by,
                    created_dt,
                    changed_by,
                    changed_dt,
                    CONCAT(process_id,transaction_id) as process_id
                FROM 
                    tb_t_payroll_loan
                WHERE (1=1)
                AND process_id = '{$process_id}'
                AND valid_flg = '1'
            ";
        $executeInsertInto = $this->db->query($sql . " AND update_flg = '0'");
        
        if(!$executeInsertInto) throw new Exception('Failed execute importExcel: line 93');

        $updateTableData = $this->db->table($this->table);
        $updateTableData->where(array('valid_flg' => '1', 'update_flg' => '1', 'process_id' => $process_id));
        $updateData = $updateTableData->get()->getResult();
        return true;
    }
}