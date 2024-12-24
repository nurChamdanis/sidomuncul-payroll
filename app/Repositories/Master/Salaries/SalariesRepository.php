<?php

namespace App\Repositories\Master\Salaries;

/**
 * @author luthfi.hakim@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\BaseRepository;
use stdClass;

class SalariesRepository extends BaseRepository{
    protected $table = 'tb_m_payroll_basic_salary';
    protected $tb_m_employee = 'tb_m_employee';
    protected $tb_m_cost_center = 'tb_m_cost_center';
    protected $tb_m_company = 'tb_m_company';
    protected $tb_m_work_unit = 'tb_m_work_unit';
    protected $tb_m_role = 'tb_m_role';
    protected $tb_m_employee_group = 'tb_m_employee_group';
    protected $tb_m_system = 'tb_m_system_payroll';
    protected $tb_m_payroll_allowance = 'tb_m_payroll_allowances';
    protected $tb_m_payroll_allowance_area = 'tb_m_payroll_allowances_area';
    protected $tb_m_payroll_deduction = 'tb_m_payroll_deductions';
    protected $tb_m_payroll_deduction_area = 'tb_m_payroll_deductions_area';
    protected $tb_m_payroll_salary_allowance = 'tb_m_payroll_basic_salary_allowances';
    protected $tb_m_payroll_salary_deduction = 'tb_m_payroll_basic_salary_deductions';
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
            {$this->tb_m_employee_group}.employee_group_id,
            {$this->tb_m_employee}.role_id,
            {$this->tb_m_employee}.no_reg, 
            {$this->tb_m_company}.company_name, 
            {$this->tb_m_work_unit}.name as work_unit_name, 
            {$this->tb_m_role}.role_name as role_name
        ");

        $query->join($this->tb_m_employee, "{$this->tb_m_employee}.employee_id = {$this->table}.employee_id", "left");
        $query->join($this->tb_m_company, "{$this->tb_m_company}.company_id = {$this->table}.company_id", "left");
        $query->join($this->tb_m_work_unit, "{$this->tb_m_work_unit}.work_unit_id = {$this->table}.work_unit_id", "left");
        $query->join($this->tb_m_role, "{$this->tb_m_role}.role_id = {$this->tb_m_employee}.role_id", "left");
        $query->join($this->tb_m_employee_group, "{$this->tb_m_employee_group}.employee_group_id = {$this->tb_m_employee}.sap_employee_grp", "left");
        $this->customTable = "({$query->getCompiledSelect()}) master_salaries";   
    }
    
    public function where($query, $filters) {
        $period_from = $filters['period_from'] ?? "";
        $period_to = $filters['period_to'] ?? "";
        $history_flg = $filters['history_flg'] ?? "";
    
        foreach ($filters as $key => $value) {
            if (!empty($value)) {
                if (in_array($key, ["company_id", "work_unit_id", "employee_id", "role_id", "employee_group_id"])) {
                    $query->where($key, $value);
                }
            }
        }
    
        if ($period_from && $period_to) {
            $query->where('effective_date_start >=', $period_from);
            $query->groupStart();
                $query->where('effective_date_end <=', $period_to);
                $query->orWhere('effective_date_end IS NULL');
            $query->groupEnd();
        }

        if($history_flg == '0'){
            $query->where('history_flg', $history_flg);
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
            {$this->tb_m_employee_group}.employee_group_id,
            {$this->tb_m_employee}.role_id,
            {$this->tb_m_employee}.no_reg, 
            {$this->tb_m_company}.company_name, 
            {$this->tb_m_company}.company_code, 
            {$this->tb_m_work_unit}.name as work_unit_name, 
            {$this->tb_m_role}.role_name as role_name,
            ptkp_system.system_value_txt as status_ptkp_name,
            employee_category_system.system_value_txt as employee_category_name
        ");

        $query->join($this->tb_m_employee, "{$this->tb_m_employee}.employee_id = {$this->table}.employee_id", "left");
        $query->join($this->tb_m_company, "{$this->tb_m_company}.company_id = {$this->table}.company_id", "left");
        $query->join($this->tb_m_work_unit, "{$this->tb_m_work_unit}.work_unit_id = {$this->table}.work_unit_id", "left");
        $query->join($this->tb_m_role, "{$this->tb_m_role}.role_id = {$this->tb_m_employee}.role_id", "left");
        $query->join($this->tb_m_employee_group, "{$this->tb_m_employee_group}.employee_group_id = {$this->tb_m_employee}.sap_employee_grp", "left");
        $query->join("{$this->tb_m_system} as ptkp_system", 
        "ptkp_system.system_code = {$this->table}.status_ptkp 
        AND ptkp_system.system_type = 'status_ptkp'
        ", "left");
        $query->join("{$this->tb_m_system} as employee_category_system", 
        "employee_category_system.system_code = {$this->table}.employee_category 
        AND employee_category_system.system_type = 'area_group'
        ", "left");

        foreach ($filters as $key => $value) {
            $query->where($key, $value);
        }

        return $query->get()->getRow();
    }

    /**
     * @var array $filters
     * @return array $data
     * ----------------------------------------------------
     * name: findByOtherKey(filters)
     * desc: Retrieving data with custom condition
     */
    public function findAllowanceByOtherKey(array $filters) : array
    {
        $query = $this->db->table($this->tb_m_payroll_allowance);
        $query->select("
            {$this->tb_m_payroll_allowance}.*,  
            {$this->tb_m_payroll_allowance_area}.allowance_area_id, 
        ");
        $query->join($this->tb_m_payroll_allowance_area, "{$this->tb_m_payroll_allowance_area}.allowance_id = {$this->tb_m_payroll_allowance}.allowance_id", "left");

        $work_unit_id = $filters['work_unit_id'] ?? "";
        $employee_group = $filters['employee_group'] ?? "";

        if (!empty($work_unit_id) || !empty($employee_group)) {
            $query->groupStart();
            if (!empty($work_unit_id)) {
                $query->groupStart();
                $query->where("area_id", $work_unit_id);
                $query->where("area_type", "0");
                $query->groupEnd();
            }
            
            if (!empty($employee_group)) {
                $query->orGroupStart();
                $query->where("area_id", $employee_group);
                $query->where("area_type", "1");
                $query->groupEnd();
            }
            $query->groupEnd();
        }

        foreach ($filters as $key => $value) {
            if($key != 'work_unit_id' && $key != 'employee_group'){
                $query->where($key, $value);
            }
        }

        $result = $query->get()->getResult();

        if (empty($result)) {
            return [];
        }

        return $result;
    }

    /**
     * @var array $filters
     * @return array $data
     * ----------------------------------------------------
     * name: findByOtherKey(filters)
     * desc: Retrieving data with custom condition
     */
    public function findDeductionByOtherKey(array $filters) : array
    {
        $query = $this->db->table($this->tb_m_payroll_deduction);
        $query->select("
            {$this->tb_m_payroll_deduction}.*,  
            {$this->tb_m_payroll_deduction_area}.deduction_area_id, 
        ");
        $query->join($this->tb_m_payroll_deduction_area, "{$this->tb_m_payroll_deduction_area}.deduction_id = {$this->tb_m_payroll_deduction}.deduction_id", "left");

        $work_unit_id = $filters['work_unit_id'] ?? "";
        $employee_group = $filters['employee_group'] ?? "";

        if (!empty($work_unit_id) || !empty($employee_group)) {
            $query->groupStart();
            
            if (!empty($work_unit_id)) {
                $query->groupStart();
                $query->where("area_id", $work_unit_id);
                $query->where("area_type", "0");
                $query->groupEnd();
            }
            
            if (!empty($employee_group)) {
                $query->orGroupStart();
                $query->where("area_id", $employee_group);
                $query->where("area_type", "1");
                $query->groupEnd();
            }
            
            $query->groupEnd();
        }

        foreach ($filters as $key => $value) {
            if($key != 'work_unit_id' && $key != 'employee_group'){
                $query->where($key, $value);
            }
        }

        $result = $query->get()->getResult();

        if (empty($result)) {
            return [];
        }
        
        return $result;
    }

    /**
     * @var array $filters
     * @return array $data
     * ----------------------------------------------------
     * name: findByOtherKey(filters)
     * desc: Retrieving data with custom condition
     */
    public function findDeductionSalaryByOtherKey(array $filters) : array
    {
        $query = $this->db->table($this->tb_m_payroll_salary_deduction);
        $query->select("
            {$this->tb_m_payroll_salary_deduction}.*,  
            {$this->tb_m_payroll_salary_deduction}.deductions_value as default_value,
            {$this->tb_m_payroll_deduction}.deduction_name, 
        ");
        $query->join($this->tb_m_payroll_deduction, "{$this->tb_m_payroll_salary_deduction}.deduction_id = {$this->tb_m_payroll_deduction}.deduction_id", "left");

        foreach ($filters as $key => $value) {
            $query->where($key, $value);
        }

        $result = $query->get()->getResult();

        if (empty($result)) {
            return [];
        }
        
        return $result;
    }

    /**
     * @var array $filters
     * @return array $data
     * ----------------------------------------------------
     * name: findByOtherKey(filters)
     * desc: Retrieving data with custom condition
     */
    public function findAllowanceSalaryByOtherKey(array $filters) : array
    {
        $query = $this->db->table($this->tb_m_payroll_salary_allowance);
        $query->select("
            {$this->tb_m_payroll_salary_allowance}.*,
            {$this->tb_m_payroll_salary_allowance}.allowances_value as default_value,
            {$this->tb_m_payroll_allowance}.allowance_name, 
        ");
        $query->join($this->tb_m_payroll_allowance, "{$this->tb_m_payroll_salary_allowance}.allowance_id = {$this->tb_m_payroll_allowance}.allowance_id", "left");

        foreach ($filters as $key => $value) {
            $query->where($key, $value);
        }

        $result = $query->get()->getResult();

        if (empty($result)) {
            return [];
        }
        
        return $result;
    }

}