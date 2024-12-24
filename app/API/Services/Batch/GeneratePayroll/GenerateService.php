<?php

namespace App\API\Services\Batch\GeneratePayroll;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\API\Repositories\Batch\GeneratePayroll\GenerateAttendanceRepository;

class GenerateService{
    protected mixed $generateAttendanceRepository;
    
    public function __construct()
    {
        $this->generateAttendanceRepository = new GenerateAttendanceRepository();
    }

    public function generateAttendance($payload = array())
    {
        $repository = $this;
        $error = null;
        
        $result = queryTransaction(function() use ($repository, $payload) 
        {
            $processFlg = isset($payload['process_flg']) ? $payload['process_flg'] : '0';
            $repository->generateAttendanceRepository->generate($processFlg);
            return true;
        }, 
        $error);

        if ($result === false) {
            return array(
                'rc' => 500,
                'status' => false,
                'data'=> null,
                'message' => "Failed Generate Payroll Get Attendance. ( {$error} )"
            );
        }
        
        return array(
            'rc' => 200,
            'status' => true,
            'data'=> null,
            'message' => 'Successfully Generate Payroll Get Attendance.'
        );
    }
}