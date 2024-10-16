<?php

namespace App\Controllers\Shared;

use App\Core\CoreController;
use App\Services\Logs\PayrollLogService;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @created_dt 2024-05-27 
 * --------------------------------
 * @modified luthfi.aziz@arkamaya.co.id on May 2024
 */
class PayrollLogsController extends CoreController
{
    protected $function_id = 302;
    protected $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = new PayrollLogService();
    }
    
    /**
     * @return json
     * ----------------------------------------------------
     * Loaded Ajax Inquiry
     */
    public function getDataTable()
    {
        ['data'      => $data] = $this->service->datatable($this->request->getPost());

        return $this->responseSuccess($data,'Successfully loaded data');
    }
}