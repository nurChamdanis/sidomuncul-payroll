<?php

namespace App\API\V1\Batch\GeneratePayroll;

use App\API\Services\Batch\GeneratePayroll\GenerateService;
use App\API\V1\APIController;

class GenerateController extends APIController{
    protected $serviceGeneratePayroll;
    public function __construct()
    {
        $this->serviceGeneratePayroll = new GenerateService();
    }

    public function generateAttendances(){
        [
            'status' => $status, 
            'message' => $message, 
            'data' => $data, 
            'rc' => $rc
        ] = $this->serviceGeneratePayroll->generateAttendance($this->request->getGet());
        return $status ? $this->responseSuccess($data, $message, $rc) : $this->responseError($data, $message, $rc);
    }
}