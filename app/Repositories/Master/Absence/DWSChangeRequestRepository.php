<?php

namespace App\Repositories\Master\Absence;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\BaseRepository;

class DWSChangeRequestRepository extends BaseRepository{
    protected $table = 'tb_r_dws_change_request';

    public function __construct()
    {
        parent::__construct();
    }
    
    public function getAllData($period_start = '', $period_end = '')
    {
        $query = $this->db->table($this->table);
        $query->select("
            {$this->table}.request_dws_id,
            {$this->table}.company_id,
            {$this->table}.work_unit_id,
            {$this->table}.employee_id,
            {$this->table}.request_date,
            {$this->table}.dws_schedule_before,
            {$this->table}.dws_schedule_after,
            {$this->table}.dws_status
        ");
        $query->where('dws_status', '1');

        if(!empty($period_start) && !empty($period_end)){
            $query->where("request_date BETWEEN '{$period_start}' AND '{$period_end}'");
        }
        
        if(!empty($period_start) && empty($period_end)){
            $query->where("request_date >= '{$period_start}'");
        }

        if(!empty($period_end) && empty($period_start)){
            $query->where("request_date <= '{$period_end}'");
        }
        
        $data = $query->get()->getResult();
        return !empty($data) ? $data : array();
    }
}