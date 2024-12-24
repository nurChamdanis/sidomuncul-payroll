<?php

namespace App\Controllers\Payroll;

use App\Core\CoreController;
use App\Services\Master\MasterSystemService;
use App\Services\Options\OptionsAreaService;
use App\Services\Options\OptionsCompanyService;
use App\Services\Options\OptionsEmployeeService;
use App\Services\Options\OptionsRoleService;
use App\Services\Payroll\GeneratePayroll\EmployeeListService;
use App\Services\Payroll\GeneratePayroll\GeneratePayrollService;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @created_dt 2024-06-20
 */
class GeneratePayrollController extends CoreController
{
    protected $function_id = 501;
    protected $service;
    protected $serviceOptionsCompany;
    protected $serviceOptionsRole; // Org. Unit
    protected $serviceOptionsArea;
    protected $serviceOptionsEmployee;
    protected $serviceEmployeeList;
    protected $serviceSystem;

    public function __construct()
    {
        parent::__construct();
        $this->service = new GeneratePayrollService();
        $this->serviceOptionsCompany = new OptionsCompanyService();
        $this->serviceOptionsRole = new OptionsRoleService();
        $this->serviceOptionsArea = new OptionsAreaService();
        $this->serviceOptionsEmployee = new OptionsEmployeeService();
        $this->serviceSystem = new MasterSystemService();
        $this->serviceEmployeeList = new EmployeeListService();
    }

    /**
     * @return view
     * ----------------------------------------------------
     * Page View Inquiry
     */
    public function index()
    {
        $data = get_title($this->menu, 'generate_payroll');
        $data['jsapp']  = array_merge(
            $this->defaultJs,
            array(
                'shared/common_inquiry',
                'shared/datatable',
                'payroll/generate_payroll/inquiry'
            )
        );

        return $this->loadView('payroll/generate_payroll/index', $data);
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

    /**
     * @return view
     * ----------------------------------------------------
     * Page View Create
     */
    public function generate()
    {
        $data           = get_title($this->menu, 'generate_payroll');
        $data['jsapp']  = array_merge(
            $this->defaultJs,
            array(
                'payroll/generate_payroll/generate',
                'shared/datatable',
                'payroll/generate_payroll/utility',
            )
        );
        
        $service_data                   = $this->serviceSystem->getPayrollGenerateOptions();
        $data['payrollGenerateOptions'] = $service_data['status'] ? $service_data['data'] : array();
        
        return $this->loadView('payroll/generate_payroll/generate', $data);
    }

    /**
     * @return json
     * ----------------------------------------------------
     * Action Get Options
     */
    public function getOptionsCompany()
    {
        [
        'data' => $data, 'status' => $status, 'message' => $message,
        ] = $this->serviceOptionsCompany->getOptions($this->input->getGet());

        return $status ? $this->responseSuccess($data, $message) : $this->responseError($data, $message);
    }

    /**
     * @return json
     * ----------------------------------------------------
     * Action Get Options
     */
    public function getOptionsArea()
    {
        [
        'data' => $data, 'status' => $status, 'message' => $message,
        ] = $this->serviceOptionsArea->getOptions($this->input->getGet());

        return $status ? $this->responseSuccess($data, $message) : $this->responseError($data, $message);
    }

    /**
     * @return json
     * ----------------------------------------------------
     * Action Get Options
     */
    public function getOptionsRole()
    {
        [
        'data' => $data, 'status' => $status, 'message' => $message,
        ] = $this->serviceOptionsRole->getOptions($this->input->getGet());

        return $status ? $this->responseSuccess($data, $message) : $this->responseError($data, $message);
    }

    /**
     * @return json
     * ----------------------------------------------------
     * Action Get Options
     */
    public function getOptionsEmployee()
    {
        [
        'data' => $data, 'status' => $status, 'message' => $message,
        ] = $this->serviceOptionsEmployee->getOptions($this->input->getGet());

        return $status ? $this->responseSuccess($data, $message) : $this->responseError($data, $message);
    }
    
    /**
     * @return json
     * ----------------------------------------------------
     * Loaded Ajax Inquiry
     */
    public function getEmployeeListDataTable()
    {
        ['data'      => $data] = $this->serviceEmployeeList->datatable($this->request->getPost());

        return $this->responseSuccess($data,'Successfully loaded data');
    }
    
    
    /**
     * @return json
     * ----------------------------------------------------
     * Loaded Ajax Inquiry
     */
    public function getAllEmployeeCheckAll()
    {
        ['data'      => $data] = $this->serviceEmployeeList->getCheckAllEmployee($this->request->getPost());

        return $this->responseSuccess($data,'Successfully loaded data');
    }

    /**
     * @return json
     * ----------------------------------------------------
     * Action Create
     */
    public function actionCreate()
    {
        [
        'data' => $data, 'status' => $status, 'message' => $message,
        ] = $this->service->create($this->request->getPost());

        if($status === false) return $this->responseError($data, $message);
        
        return $this->responseSuccess($data, $message);
    }
    
    /**
     * @return file
     * ----------------------------------------------------
     * Action Download Excel
     */
    public function actionDownloadExcel()
    {       
        [
        'data' => $data, 'status' => $status, 'message' => $message,
        ] = $this->service->downloadExcel($this->input->getPost());
        
        if (file_exists($data)) {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . basename($data) . '"');
            header('Cache-Control: max-age=0');
            readfile($data);
            unlink($data);
            exit;
        } else {
            data_dump($this->input->getPost());
        }
    }
}