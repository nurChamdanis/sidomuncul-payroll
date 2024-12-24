<?php

namespace App\Repositories\Master\Loan;

/**
 * @author luthfi.hakim@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\BaseRepository;
use stdClass;

class LoanRepository extends BaseRepository{
    protected $table = 'tb_r_payroll_loan';
    protected $tb_m_employee = 'tb_m_employee';
    protected $tb_m_cost_center = 'tb_m_cost_center';
    protected $tb_m_company = 'tb_m_company';
    protected $tb_m_work_unit = 'tb_m_work_unit';
    protected $tb_m_role = 'tb_m_role';
    protected $tb_m_position = 'tb_m_position';
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
            {$this->tb_m_employee}.position_id,
            CONCAT({$this->tb_m_cost_center}.cost_center_code, '-', {$this->tb_m_cost_center}.cost_center_desc) AS cost_center_desc,
            {$this->tb_m_employee}.no_reg, 
            {$this->tb_m_company}.company_name, 
            {$this->tb_m_work_unit}.name as work_unit_name, 
            {$this->tb_m_role}.role_name as role_name, 
            loan_type_system.system_value_txt as loan_type_name,
            loan_duration_system.system_value_txt as loan_duration_name
        ");

        $query->join($this->tb_m_employee, "{$this->tb_m_employee}.employee_id = {$this->table}.employee_id", "left");
        $query->join($this->tb_m_company, "{$this->tb_m_company}.company_id = {$this->table}.company_id", "left");
        $query->join($this->tb_m_work_unit, "{$this->tb_m_work_unit}.work_unit_id = {$this->table}.work_unit_id", "left");
        $query->join($this->tb_m_role, "{$this->tb_m_role}.role_id = {$this->tb_m_employee}.role_id", "left");
        $query->join($this->tb_m_position, "{$this->tb_m_employee}.position_id = {$this->tb_m_position}.position_id", "left");
        $query->join($this->tb_m_cost_center, "{$this->tb_m_cost_center}.cost_center_id = {$this->tb_m_employee}.cost_center", "left");
        $query->join("{$this->tb_m_system} as loan_type_system", 
        "loan_type_system.system_code = {$this->table}.loan_type 
        AND loan_type_system.system_type = 'loan_type'
        ", "left");
        $query->join("{$this->tb_m_system} as loan_duration_system", 
        "loan_duration_system.system_code = {$this->table}.loan_duration 
        AND loan_duration_system.system_type = 'loan_duration'
        ", "left");
        $this->customTable = "({$query->getCompiledSelect()}) master_loan";
        
    }
    
    public function where($query, $filters) {
        $period_from = $filters['period_from'] ?? "";
        $period_to = $filters['period_to'] ?? "";
    
        foreach ($filters as $key => $value) {
            if (!empty($value)) {
                if (in_array($key, ["company_id", "work_unit_id", "employee_id", "cost_center_id", "role_id"])) {
                    $query->where($key, $value);
                }
            }
        }
    
        if ($period_from && $period_to) {
            $query->where('deduction_period_start >=', $period_from);
            $query->where('deduction_period_end <=', $period_to);
        }
    }

    /**
     * @var array $filters
     * @return array $data
     * ----------------------------------------------------
     * name: findByOtherKey(filters)
     * desc: Retrieving data with custom condition
     */
    public function findByOtherKey(array $filters) : stdClass
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

        foreach ($filters as $key => $value) {
            $query->where($key, $value);
        }

        return $query->get()->getRow();
    }

    public function whereAccessData($query){
        $query->where(access_data(fields:'company_id,work_unit_id,role_id,employee_id,position_id', type: 'where_in'));
    }
}