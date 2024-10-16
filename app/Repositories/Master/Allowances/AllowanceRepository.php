<?php

namespace App\Repositories\Master\Allowances;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\BaseRepository;
use stdClass;

class AllowanceRepository extends BaseRepository{
    protected $table = 'tb_m_payroll_allowances';
    protected $tb_m_allowances_area = 'tb_m_payroll_allowances_area';
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

        $allowanceAreaAliasName = "
            SELECT 
                GROUP_CONCAT(WORKUNIT.name SEPARATOR ',') as area_name  
            FROM {$this->tb_m_allowances_area} ALLOWANCEAREA 
            LEFT JOIN {$this->tb_m_work_unit} WORKUNIT ON WORKUNIT.work_unit_id = ALLOWANCEAREA.area_id
            WHERE 
                ALLOWANCEAREA.allowance_id = {$this->table}.allowance_id
            AND
                ALLOWANCEAREA.area_type = '0'
        ";
        if(!empty($work_unit_id)) $allowanceAreaAliasName .= " AND WORKUNIT.work_unit_id = '{$work_unit_id}'";
        $allowanceAreaAliasName .= " AND ".access_data(alias: 'WORKUNIT', fields: 'work_unit_id', type: 'where_in');
        
        $allowanceGroupAliasName = "
            SELECT 
                GROUP_CONCAT(SYSTEM.system_value_txt SEPARATOR ',') as group_name
            FROM {$this->tb_m_allowances_area} ALLOWANCEAREA 
            LEFT JOIN {$this->tb_m_system} SYSTEM ON SYSTEM.system_code = ALLOWANCEAREA.area_id
            WHERE 
                ALLOWANCEAREA.allowance_id = {$this->table}.allowance_id AND SYSTEM.system_type = 'area_group'
            AND
                ALLOWANCEAREA.area_type = '1'
        ";

        $allowanceAreaAliasId = "
            SELECT 
                REPLACE(GROUP_CONCAT(TRIM(ALLOWANCEAREA.area_id) SEPARATOR ','), ' ','') as area_name_id
            FROM {$this->tb_m_allowances_area} ALLOWANCEAREA 
            LEFT JOIN {$this->tb_m_work_unit} WORKUNIT ON WORKUNIT.work_unit_id = ALLOWANCEAREA.area_id
            WHERE 
                ALLOWANCEAREA.allowance_id = {$this->table}.allowance_id
            AND
                ALLOWANCEAREA.area_type = '0'
        ";
        if(!empty($work_unit_id)) $allowanceAreaAliasId .= " AND WORKUNIT.work_unit_id = '{$work_unit_id}'";
        $allowanceAreaAliasId .= " AND ".access_data(alias: 'WORKUNIT', fields: 'work_unit_id', type: 'where_in');
        
        $allowanceGroupAliasId = "
            SELECT 
                REPLACE(GROUP_CONCAT(TRIM(ALLOWANCEAREA.area_id) SEPARATOR ','),' ','') as group_name_id
            FROM {$this->tb_m_allowances_area} ALLOWANCEAREA 
            LEFT JOIN {$this->tb_m_system} SYSTEM ON SYSTEM.system_code = ALLOWANCEAREA.area_id AND SYSTEM.system_type = 'area_group'
            WHERE 
                ALLOWANCEAREA.allowance_id = {$this->table}.allowance_id 
            AND
                ALLOWANCEAREA.area_type = '1'
        ";

        $query = $this->db->table($this->table);
        $query->select("
            {$this->table}.process_id,
            {$this->table}.allowance_id,
            {$this->table}.company_id,
            {$this->table}.allowance_code,
            {$this->table}.allowance_name,
            {$this->table}.default_value,
            {$this->table}.minimum_working_period,
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
            ({$allowanceAreaAliasName}) as area_name, 
            ({$allowanceAreaAliasId}) as area_name_id, 
            ({$allowanceGroupAliasName}) as group_name,
            ({$allowanceGroupAliasId}) as group_name_id
        ");
        $query->join($this->tb_m_company, "{$this->tb_m_company}.company_id = {$this->table}.company_id");
        $query->join($this->tb_m_employee . " CREATEDBY", "CREATEDBY.no_reg = {$this->table}.created_by", "left");
        $query->join($this->tb_m_employee . " CHANGEDBY", "CHANGEDBY.no_reg = {$this->table}.changed_by", "left");
        
        $this->customTable = "({$query->getCompiledSelect()}) master_allowances";
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

        $query = $this->db->table("(".$queryGroup->getCompiledSelect().") tb_m_allowances");

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
        $allowanceAreaAliasName = "
            SELECT 
                GROUP_CONCAT(WORKUNIT.name SEPARATOR ',') as area_name  
            FROM {$this->tb_m_allowances_area} ALLOWANCEAREA 
            LEFT JOIN {$this->tb_m_work_unit} WORKUNIT ON WORKUNIT.work_unit_id = ALLOWANCEAREA.area_id
            WHERE 
                ALLOWANCEAREA.allowance_id = {$this->table}.allowance_id
            AND
                ALLOWANCEAREA.area_type = '0'
        ";
        
        $allowanceGroupAliasName = "
            SELECT 
                GROUP_CONCAT(SYSTEM.system_value_txt SEPARATOR ',') as group_name
            FROM {$this->tb_m_allowances_area} ALLOWANCEAREA 
            LEFT JOIN {$this->tb_m_system} SYSTEM ON SYSTEM.system_code = ALLOWANCEAREA.area_id
            WHERE 
                ALLOWANCEAREA.allowance_id = {$this->table}.allowance_id AND SYSTEM.system_type = 'area_group'
            AND
                ALLOWANCEAREA.area_type = '1'
        ";

        $query = $this->db->table($this->table);
        $query->select("
            {$this->table}.allowance_id,
            {$this->table}.allowance_code,
            {$this->table}.allowance_name,
            {$this->table}.default_value,
            {$this->table}.minimum_working_period,
            CALCULATION_TYPE.system_value_txt calculation_type,
            CALCULATION_MODE.system_value_txt calculation_mode,
            {$this->tb_m_gl_account}.gl_name as gl_account,
            {$this->table}.is_active,
            IFNULL(CREATEDBY.employee_name,{$this->table}.created_by) as created_by, 
            {$this->table}.created_dt,
            IFNULL(CHANGEDBY.employee_name,{$this->table}.changed_by) as changed_by, 
            {$this->table}.changed_dt,
            {$this->table}.effective_date,
            {$this->table}.effective_date_end,
            {$this->tb_m_company}.company_name, 
            ({$allowanceAreaAliasName}) as area_name, 
            ({$allowanceGroupAliasName}) as group_name
        ");
        $query->join($this->tb_m_company, "{$this->tb_m_company}.company_id = {$this->table}.company_id");
        $query->join($this->tb_m_system . ' CALCULATION_TYPE', "CALCULATION_TYPE.system_code = {$this->table}.calculation_type AND LOWER(CALCULATION_TYPE.system_type) = 'calculation_mode'", 'left');
        $query->join($this->tb_m_system . ' CALCULATION_MODE', "CALCULATION_MODE.system_code = {$this->table}.calculation_mode AND LOWER(CALCULATION_MODE.system_type) = 'calculation_type'", 'left');
        $query->join($this->tb_m_gl_account, "{$this->tb_m_gl_account}.gl_id = {$this->table}.gl_id", 'left');
        $query->join($this->tb_m_employee . " CREATEDBY", "CREATEDBY.no_reg = {$this->table}.created_by", "left");
        $query->join($this->tb_m_employee . " CHANGEDBY", "CHANGEDBY.no_reg = {$this->table}.changed_by", "left");
        
        $table = "({$query->getCompiledSelect()}) master_allowances";

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