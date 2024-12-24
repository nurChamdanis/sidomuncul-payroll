<?php

namespace App\Repositories\Master\Absence;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\BaseRepository;

class PWSScheduleRepository extends BaseRepository{
    protected $table = 'tb_m_pws_schedule';
    protected $tb_m_employee_pws = 'tb_m_employee_pws';

    public function __construct()
    {
        parent::__construct();
    }
}