<?php

namespace App\Repositories\Payroll\GeneratePayroll;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\BaseRepository;

class GeneratePayrollRepository extends BaseRepository{
    protected $table = 'tb_r_payroll_transaction';
    protected $tb_r_payroll_transaction_employee = 'tb_r_payroll_transaction_employee';
    protected $tb_m_company = 'tb_m_company';
    protected $tb_m_workunit = 'tb_m_work_unit';
    protected $tb_m_role = 'tb_m_role';
    protected $tb_m_employee = 'tb_m_employee';
    protected $modifyTableAndCondition = true;

    /**
     * @return string $compiledselect
     * ----------------------------------------------------
     * name: newTableAliases()
     * desc: Retrieving subquery for new aliases table
     */
    public function setCustomTable()
    {
        $allowancesWithPph21 = decryptString("{$this->table}.total_allowances_with_pph21");
        $allowancesWithoutPph21 = decryptString("{$this->table}.total_allowances_without_pph21");
        $deductionsWithPph21 = decryptString("{$this->table}.total_deductions_with_pph21");
        $deductionsWithoutPph21 = decryptString("{$this->table}.total_deductions_without_pph21");
        $totalBruto = decryptString("{$this->table}.total_bruto");
        $totalPph21 = decryptString("{$this->table}.total_pph21");
        $totalThp = decryptString("{$this->table}.total_thp");

        $query = $this->db->table($this->table);
        $query->select("
            {$this->table}.payroll_transaction_id,
            {$this->table}.company_id,
            {$this->tb_m_company}.company_name,
            {$this->table}.work_unit_id,
            {$this->tb_m_workunit}.name as work_unit_name,
            {$this->table}.role_id,
            {$this->tb_m_role}.role_name,
            {$this->table}.payroll_title,
            {$this->table}.payroll_period,
            {$this->table}.period_start,
            {$this->table}.period_end,
            {$this->table}.attendance_period_start,
            {$this->table}.attendance_period_end,
            {$this->table}.deduction_flg,
            {$this->table}.allowance_flg,
            {$this->table}.pph21_flg,
            {$this->table}.compensation_flg,
            {$this->table}.attendance_deduction_flg,
            CASE 
                WHEN {$this->table}.total_allowances_with_pph21 IS NULL 
                    OR {$this->table}.total_allowances_without_pph21 IS NULL 
                THEN NULL
                ELSE ({$allowancesWithPph21} + {$allowancesWithoutPph21}) 
            END as total_allowances,
            CASE 
                WHEN {$this->table}.total_deductions_with_pph21 IS NULL 
                    OR {$this->table}.total_deductions_without_pph21 IS NULL 
                THEN NULL
                ELSE ({$deductionsWithPph21} + {$deductionsWithoutPph21}) 
            END as total_deductions,
            CASE 
                WHEN {$this->table}.total_bruto IS NULL
                THEN NULL
                ELSE {$totalBruto}
            END as total_bruto,
            CASE 
                WHEN {$this->table}.total_pph21 IS NULL 
                THEN NULL
                ELSE {$totalPph21} 
            END as total_pph21,
            {$this->table}.total_employee,
            CASE 
                WHEN {$this->table}.total_thp IS NULL 
                THEN NULL
                ELSE {$totalThp} 
            END as total_thp,
            {$this->table}.process_flg,
            {$this->table}.lock_flg,
            {$this->table}.posting_flg,
            IFNULL(CREATEDBY.employee_name,{$this->table}.created_by) as created_by, 
            {$this->table}.created_dt,
            IFNULL(CHANGEDBY.employee_name,{$this->table}.changed_by) as changed_by, 
            {$this->table}.changed_dt
        ");
        $query->join($this->tb_m_company, "{$this->tb_m_company}.company_id = {$this->table}.company_id", "left");
        $query->join($this->tb_m_workunit, "{$this->tb_m_workunit}.work_unit_id = {$this->table}.work_unit_id", "left");
        $query->join($this->tb_m_role, "{$this->tb_m_role}.role_id = {$this->table}.role_id", "left");
        $query->join($this->tb_m_employee . " CREATEDBY", "CREATEDBY.no_reg = {$this->table}.created_by", "left");
        $query->join($this->tb_m_employee . " CHANGEDBY", "CHANGEDBY.no_reg = {$this->table}.changed_by", "left");
        $this->customTable = "({$query->getCompiledSelect()}) payroll_transactions";
    }
    
    public function whereAccessData($query){
        $query->where(access_data(fields:'company_id,work_unit_id,role_id', type: 'where_in'));
    }
    
    public function getAllData($payroll_period = '')
    {
        $query = $this->db->table($this->table);
        $query->select("
            {$this->table}.payroll_transaction_id,
            {$this->table}.payroll_period,
            {$this->table}.process_flg,
            {$this->tb_r_payroll_transaction_employee}.employee_id
        ");
        $query->join($this->tb_r_payroll_transaction_employee, "{$this->tb_r_payroll_transaction_employee}.payroll_transaction_id = {$this->table}.payroll_transaction_id");
        
        if(!empty($payroll_period)){
            $query->where('payroll_period', $payroll_period);
        }

        $query->where('process_flg', '1');
        $data = $query->get()->getResult();
        return !empty($data) ? $data : array();
    }
}