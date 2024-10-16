<?php

namespace App\Repositories\Master\Absence;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\BaseRepository;

class DWSScheduleRepository extends BaseRepository{
    protected $table = 'tb_m_dws_schedule';
    protected $tb_m_employee_pws = 'tb_m_employee_pws';

    public function __construct()
    {
        parent::__construct();
    }
    
    public function getAllData()
    {
        $query = $this->db->table($this->table);
        $query->select("
            {$this->table}.dws_id,
            {$this->table}.dws_code,
            {$this->table}.dws_name,
        ");
        $data = $query->get()->getResult();
        return !empty($data) ? $data : array();
    }
}