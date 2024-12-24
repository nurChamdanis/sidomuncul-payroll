<?php

namespace App\Repositories\Master\Deductions;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\BaseRepository;
use stdClass;

class DeductionRepository extends BaseRepository{
    protected $table = 'tb_m_payroll_deductions';
    protected $tb_m_deductions_area = 'tb_m_payroll_deductions_area';
    protected $tb_m_company = 'tb_m_company';
    protected $tb_m_work_unit = 'tb_m_work_unit';
    protected $tb_m_system = 'tb_m_system_payroll';
    protected $tb_m_employee = 'tb_m_employee';
    protected $tb_m_gl_account = 'tb_m_payroll_gl';
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
        $work_unit_id = isset($_POST['work_unit_id']) ? $_POST['work_unit_id'] : '';

        $deductionAreaAliasName = "
            SELECT 
                GROUP_CONCAT(WORKUNIT.name SEPARATOR ',') as area_name  
            FROM {$this->tb_m_deductions_area} DEDUCTIONAREA 
            LEFT JOIN {$this->tb_m_work_unit} WORKUNIT ON WORKUNIT.work_unit_id = DEDUCTIONAREA.area_id
            WHERE 
                DEDUCTIONAREA.deduction_id = {$this->table}.deduction_id
            AND
                DEDUCTIONAREA.area_type = '0'
        ";
        if(!empty($work_unit_id)) $deductionAreaAliasName .= " AND WORKUNIT.work_unit_id = '{$work_unit_id}'";
        $deductionAreaAliasName .= " AND ".access_data(alias: 'WORKUNIT', fields: 'work_unit_id', type: 'where_in');
        
        $deductionGroupAliasName = "
            SELECT 
                GROUP_CONCAT(SYSTEM.system_value_txt SEPARATOR ',') as group_name
            FROM {$this->tb_m_deductions_area} DEDUCTIONAREA 
            LEFT JOIN {$this->tb_m_system} SYSTEM ON SYSTEM.system_code = DEDUCTIONAREA.area_id
            WHERE 
                DEDUCTIONAREA.deduction_id = {$this->table}.deduction_id AND SYSTEM.system_type = 'area_group'
            AND
                DEDUCTIONAREA.area_type = '1'
        ";

        $deductionAreaAliasId = "
            SELECT 
                REPLACE(GROUP_CONCAT(TRIM(DEDUCTIONAREA.area_id) SEPARATOR ','), ' ','') as area_name_id
            FROM {$this->tb_m_deductions_area} DEDUCTIONAREA 
            LEFT JOIN {$this->tb_m_work_unit} WORKUNIT ON WORKUNIT.work_unit_id = DEDUCTIONAREA.area_id
            WHERE 
                DEDUCTIONAREA.deduction_id = {$this->table}.deduction_id
            AND
                DEDUCTIONAREA.area_type = '0'
        ";
        if(!empty($work_unit_id)) $deductionAreaAliasId .= " AND WORKUNIT.work_unit_id = '{$work_unit_id}'";
        $deductionAreaAliasId .= " AND ".access_data(alias: 'WORKUNIT', fields: 'work_unit_id', type: 'where_in');
        
        $deductionGroupAliasId = "
            SELECT 
                REPLACE(GROUP_CONCAT(TRIM(DEDUCTIONAREA.area_id) SEPARATOR ','),' ','') as group_name_id
            FROM {$this->tb_m_deductions_area} DEDUCTIONAREA 
            LEFT JOIN {$this->tb_m_system} SYSTEM ON SYSTEM.system_code = DEDUCTIONAREA.area_id AND SYSTEM.system_type = 'area_group'
            WHERE 
                DEDUCTIONAREA.deduction_id = {$this->table}.deduction_id 
            AND
                DEDUCTIONAREA.area_type = '1'
        ";

        $query = $this->db->table($this->table);
        $query->select("
            {$this->table}.process_id,
            {$this->table}.deduction_id,
            {$this->table}.company_id,
            {$this->table}.deduction_code,
            {$this->table}.deduction_name,
            {$this->table}.default_value,
            {$this->table}.calculation_type,
            {$this->table}.calculation_mode,
            {$this->table}.effective_date,
            {$this->table}.effective_date_end,
            {$this->table}.gl_id,
            {$this->table}.is_active,
            IFNULL(CREATEDBY.employee_name,{$this->table}.created_by) as created_by, 
            IFNULL(CHANGEDBY.employee_name,{$this->table}.changed_by) as changed_by, 
            {$this->table}.created_dt,
            {$this->table}.changed_dt,
            {$this->tb_m_company}.company_name, 
            ({$deductionAreaAliasName}) as area_name, 
            ({$deductionAreaAliasId}) as area_name_id, 
            ({$deductionGroupAliasName}) as group_name,
            ({$deductionGroupAliasId}) as group_name_id
        ");
        $query->join($this->tb_m_company, "{$this->tb_m_company}.company_id = {$this->table}.company_id");
        $query->join($this->tb_m_employee . " CREATEDBY", "CREATEDBY.no_reg = {$this->table}.created_by", "left");
        $query->join($this->tb_m_employee . " CHANGEDBY", "CHANGEDBY.no_reg = {$this->table}.changed_by", "left");
        
        $this->customTable = "({$query->getCompiledSelect()}) master_deductions";
    }
    
    public function where($query, $filters){
        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                if(!empty($value)){
                    if($key == "company_id"):
                        $query->where($key, $value);
                    else:
                        $query->where("FIND_IN_SET('{$value}', {$key})");
                    endif;
                }
            }
        }
    }
    
    public function whereAccessData($query){
        $query->where(access_data(fields:'company_id', type: 'where_in'));
    }
        
    /**
     * @var array $filters
     * @return array $data
     * ----------------------------------------------------
     * name: findByOtherKey(filters)
     * desc: Retrieving data with custom condition
     */
    public function findByOtherKey(array $filters) : ?stdClass
    {
        $queryGroup = $this->db->table($this->table);
        $queryGroup->select("{$this->table}.*, tb_m_company.company_code");
        $queryGroup->join("tb_m_company", "tb_m_company.company_id = {$this->table}.company_id");

        $query = $this->db->table("(".$queryGroup->getCompiledSelect().") tb_m_deductions");

        foreach ($filters as $key => $value) {
            $query->where($key, $value);
        }

        return $query->get()->getRow();
    }

    
    /**
     * @return string $compiledselect
     * ----------------------------------------------------
     * name: newTableAliases()
     * desc: Retrieving subquery for new aliases table
     */
    public function getLogHistory($payload = array())
    {
        $deductionAreaAliasName = "
            SELECT 
                GROUP_CONCAT(WORKUNIT.name SEPARATOR ',') as area_name  
            FROM {$this->tb_m_deductions_area} DEDUCTIONAREA 
            LEFT JOIN {$this->tb_m_work_unit} WORKUNIT ON WORKUNIT.work_unit_id = DEDUCTIONAREA.area_id
            WHERE 
                DEDUCTIONAREA.deduction_id = {$this->table}.deduction_id
            AND
                DEDUCTIONAREA.area_type = '0'
        ";
        
        $deductionGroupAliasName = "
            SELECT 
                GROUP_CONCAT(SYSTEM.system_value_txt SEPARATOR ',') as group_name
            FROM {$this->tb_m_deductions_area} DEDUCTIONAREA 
            LEFT JOIN {$this->tb_m_system} SYSTEM ON SYSTEM.system_code = DEDUCTIONAREA.area_id
            WHERE 
                DEDUCTIONAREA.deduction_id = {$this->table}.deduction_id AND SYSTEM.system_type = 'area_group'
            AND
                DEDUCTIONAREA.area_type = '1'
        ";

        $query = $this->db->table($this->table);
        $query->select("
            {$this->table}.deduction_id,
            {$this->table}.deduction_code,
            {$this->table}.deduction_name,
            {$this->table}.default_value,
            CALCULATION_TYPE.system_value_txt calculation_type,
            CALCULATION_MODE.system_value_txt calculation_mode,
            {$this->tb_m_gl_account}.gl_name as gl_account,
            {$this->table}.is_active,
            IFNULL(CREATEDBY.employee_name,{$this->table}.created_by) as created_by, 
            {$this->table}.created_dt,
            IFNULL(CHANGEDBY.employee_name,{$this->table}.changed_by) as changed_by, 
            {$this->table}.effective_date,
            {$this->table}.effective_date_end,
            {$this->tb_m_company}.company_name, 
            ({$deductionAreaAliasName}) as area_name, 
            ({$deductionGroupAliasName}) as group_name
        ");
        $query->join($this->tb_m_company, "{$this->tb_m_company}.company_id = {$this->table}.company_id");
        $query->join($this->tb_m_system . ' CALCULATION_TYPE', "CALCULATION_TYPE.system_code = {$this->table}.calculation_type AND LOWER(CALCULATION_TYPE.system_type) = 'calculation_mode'", 'left');
        $query->join($this->tb_m_system . ' CALCULATION_MODE', "CALCULATION_MODE.system_code = {$this->table}.calculation_mode AND LOWER(CALCULATION_MODE.system_type) = 'calculation_type'", 'left');
        $query->join($this->tb_m_gl_account, "{$this->tb_m_gl_account}.gl_id = {$this->table}.gl_id", 'left');
        $query->join($this->tb_m_employee . " CREATEDBY", "CREATEDBY.no_reg = {$this->table}.created_by", "left");
        $query->join($this->tb_m_employee . " CHANGEDBY", "CHANGEDBY.no_reg = {$this->table}.changed_by", "left");
        
        $table = "({$query->getCompiledSelect()}) master_deductions";

        $newQuery = $this->db->table($table);

        if(!empty($payload)){
            foreach ($payload as $key => $value) {
                $newQuery->where($key, $value);
            }
        }

        $data = $newQuery->get()->getRow();
        return !empty($data) ? json_encode($data) : json_encode(array());
    }
}