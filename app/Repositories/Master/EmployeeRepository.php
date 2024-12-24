<?php

namespace App\Repositories\Master;

/**
 * @author lutfi.hakim@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\BaseRepository;
use stdClass;

class EmployeeRepository extends BaseRepository{
    protected $table = 'tb_m_employee';
    protected $tb_m_company = 'tb_m_company';
    protected $tb_m_work_unit = 'tb_m_work_unit';
    protected $tb_m_role = 'tb_m_role';

    public function __construct()
    {
        parent::__construct();
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
            {$this->tb_m_company}.company_name, 
            {$this->tb_m_company}.company_code,
            {$this->tb_m_work_unit}.work_unit_id,
            {$this->tb_m_work_unit}.name as work_unit_name, 
            {$this->tb_m_role}.role_id,
            {$this->tb_m_role}.role_name, 
        ");
        $query->join($this->tb_m_company, "{$this->tb_m_company}.company_id = {$this->table}.company_id", "left");
        $query->join($this->tb_m_work_unit, "{$this->tb_m_work_unit}.work_unit_id = {$this->table}.work_unit_id", "left");
        $query->join($this->tb_m_role, "{$this->tb_m_role}.role_id = {$this->table}.role_id", "left");

        foreach ($filters as $key => $value) {
            $query->where($key, $value);
        }

        $result = $query->get()->getFirstRow();

         // Ensure we always return an stdClass instance
        if ($result === null) {
            $result = new stdClass();
            $result->employee_id = '';
        }

        return $result;
    }
}