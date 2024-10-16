<?php

namespace App\API\Repositories\Batch\GeneratePayroll;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\BaseRepository;

class GenerateAttendanceRepository extends BaseRepository{
    protected $SP_PAYROLL_GENERATE_ATTENDANCE = 'sp_PayrollGenerateAttendance(?)';

    public function __construct()
    {
        parent::__construct();
    }

    public function generate($process_flg = '0')
    {
        return $this->db->query("CALL {$this->SP_PAYROLL_GENERATE_ATTENDANCE}", [$process_flg]);
    }
}