<?php

namespace App\Repositories\Payroll\GeneratePayroll;

/**
 * @author lutfi.hakim@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\BaseRepository;
use stdClass;

class EmployeeListRepository extends BaseRepository{
    protected $table = 'tb_m_employee';
    protected $tb_m_company = 'tb_m_company';
    protected $tb_m_work_unit = 'tb_m_work_unit';
    protected $tb_m_role = 'tb_m_role';
    protected $tb_m_position = 'tb_m_position';
    protected $tb_m_pws_schedule = 'tb_m_pws_schedule';

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
            {$this->tb_m_company}.company_name,
            {$this->tb_m_work_unit}.name as work_unit_name,
            {$this->tb_m_role}.role_name,
            {$this->tb_m_position}.position_name,
            {$this->tb_m_pws_schedule}.pws_code,
            {$this->tb_m_pws_schedule}.pws_name,
            {$this->tb_m_pws_schedule}.pws_mon as mon,
            {$this->tb_m_pws_schedule}.pws_tue as tue,
            {$this->tb_m_pws_schedule}.pws_wed as wed,
            {$this->tb_m_pws_schedule}.pws_thu as thu,
            {$this->tb_m_pws_schedule}.pws_fri as fri,
            {$this->tb_m_pws_schedule}.pws_sat as sat,
            {$this->tb_m_pws_schedule}.pws_sun as sun
        ");
        $query->join($this->tb_m_company, "{$this->tb_m_company}.company_id = {$this->table}.company_id","left");
        $query->join($this->tb_m_work_unit, "{$this->tb_m_work_unit}.work_unit_id = {$this->table}.work_unit_id","left");
        $query->join($this->tb_m_role, "{$this->tb_m_role}.role_id = {$this->table}.role_id","left");
        $query->join($this->tb_m_position, "{$this->tb_m_position}.position_id = {$this->table}.position_id","left");
        $query->join($this->tb_m_pws_schedule, "{$this->tb_m_pws_schedule}.pws_id = {$this->table}.pws_id","left");
        $this->customTable = "({$query->getCompiledSelect()}) employee_list";
    }
    
    public function whereAccessData($query){
        $query->where(access_data(fields:'employee_id,company_id,work_unit_id,role_id,position_id', type: 'where_in'));
    }
}