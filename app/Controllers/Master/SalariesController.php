<?php

namespace App\Controllers\Master;

use App\Core\CoreController;
use App\Services\Master\Salaries\SalariesService;
use App\Services\Master\Loan\LoanExcelService;
use App\Services\Options\OptionsAreaService;
use App\Services\Options\OptionsCompanyService;
use App\Services\Options\OptionsRoleService;
use App\Services\Options\OptionsEmployeeService;
use App\Services\Options\OptionsCostCenterService;
use App\Services\Options\OptionsSystemService;
use App\Services\Options\OptionsEmployeeContractService;

/**
 * @author lutfi.hakim@arkamaya.co.id
 * @since 2024-05-28 
 * --------------------------------
 * @modified luthfi.hakim@arkamaya.co.id on May 2024
 */

class SalariesController extends CoreController
{
    protected $function_id = 301;
    protected $service;
    protected $serviceOptionsCompany;
    protected $serviceOptionsArea;
    protected $serviceOptionsRole;
    protected $serviceOptionsEmployeee;
    protected $serviceOptionsCostCenter;
    protected $serviceOptionsSystem;
    protected $serviceOptionEmployeeContract;
    protected $serviceExcel;

    public function __construct()
    {
        parent::__construct();
        $this->service = new SalariesService();
        $this->serviceOptionsCompany = new OptionsCompanyService();
        $this->serviceOptionsArea = new OptionsAreaService();
        $this->serviceOptionsRole = new OptionsRoleService();
        $this->serviceOptionsEmployeee = new OptionsEmployeeService();
        $this->serviceOptionsCostCenter = new OptionsCostCenterService();
        $this->serviceOptionsSystem = new OptionsSystemService();
        $this->serviceOptionEmployeeContract = new OptionsEmployeeContractService();
        $this->serviceExcel = new LoanExcelService();
    }

    /**
     * @return view
     * ----------------------------------------------------
     * Page View Inquiry
     */ 
    public function index()
    {

        $data = get_title($this->menu, 'master_salaries');
        $data['jsapp']  = array_merge(
            $this->defaultJs,
            array(
                'shared/common_inquiry',
                'shared/datatable',
                'master/salaries/inquiry'
            )
        );

        return $this->loadView('master/salaries/index', $data);
    }

    /**
     * @return view
     * ----------------------------------------------------
     * Page View Inquiry
     */
    public function create()
    {

        $data = get_title($this->menu, 'master_salaries');
        $data['jsapp']  = array_merge(
            $this->defaultJs,
            array(
                'master/salaries/create'
            )
        );

        
        // print_r($this->service->getAllowanceByKey('1', '2'));
        // die;

        
        return $this->loadView('master/salaries/create', $data);
    }

    /**
     * @return view
     * ----------------------------------------------------
     * Page View Detail
     */
    public function id($basic_salary_id)
    {
        $data           = get_title($this->menu, 'master_salaries');
        $data['jsapp']  = array_merge(
            $this->defaultJs,
            array(
                'master/salaries/id',
                'shared/datatable',
                'shared/payroll_inquiry',
            )
        );

        $data['salary'] = $this->service->getByKey($basic_salary_id)['data'];
        
        $data['function_id']    = (string) $this->function_id;
        $data['refference_id']  = $basic_salary_id;
        
        return $this->loadView('master/salaries/id', $data);
    }

    /**
     * @return view
     * ----------------------------------------------------
     * Page View Update
     */
    public function edit($basic_salary_id)
    {
        $data           = get_title($this->menu, 'master_salaries');
        $data['jsapp']  = array_merge(
            $this->defaultJs,
            array(
                'master/salaries/update',
            )
        );

        $data['salary'] = $this->service->getByKey($basic_salary_id)['data'];
        
        return $this->loadView('master/salaries/edit', $data);
    }

    /**
     * @return view
     * ----------------------------------------------------
     * Page View Upload
     */
    public function index_upload()
    {
        $data = get_title($this->menu, 'master_salaries');
        $data['jsapp']  = array_merge(
            $this->defaultJs,
            array(
                'shared/datatable',
                'master/salaries/inquiry_upload'
            )
        );

        return $this->loadView('master/salaries/index_upload', $data);
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
     * @return json
     * ----------------------------------------------------
     * Action Remove
     */
    public function actionRemove()
    {
        [
        'data' => $data, 'status' => $status, 'message' => $message,
        ] = $this->service->remove($this->request->getPost());

        return $status ? $this->responseSuccess($data, $message) : $this->responseError($data, $message);
    }

    /**
     * @return json
     * ----------------------------------------------------
     * Action Remove
     */
    public function actionRemoveSelected()
    {
        [
        'data' => $data, 'status' => $status, 'message' => $message,
        ] = $this->service->removeSelected($this->request->getPost());

        return $status ? $this->responseSuccess($data, $message) : $this->responseError($data, $message);
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
     * @return json
     * ----------------------------------------------------
     * Action Update
     */
    public function actionUpdate()
    {
        
        [
        'data' => $data, 'status' => $status, 'message' => $message
        ] = $this->service->update($this->request->getPost());

        return $status ? $this->responseSuccess($data, $message) : $this->responseError($data, $message);
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
            exit;
        } else {
            data_dump($this->input->getPost());
        }
    }

    /**
     * @return json
     * ----------------------------------------------------
     * Loaded Ajax Inquiry
     */
    public function getUploadDataTable()
    {
        ['data'      => $data] = $this->serviceExcel->datatable_uploaded($this->request->getPost());

        return $this->responseSuccess($data,'Successfully loaded data');
    }

    /**
     * @return file
     * ----------------------------------------------------
     * Action Download Excel
     */
    public function actionDownloadExcelTemplate()
    {       
        [
        'data' => $data, 'status' => $status, 'message' => $message,
        ] = $this->serviceExcel->downloadExcelTemplate($this->input->getPost());
        
        if (file_exists($data)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment;filename="' . basename($data) . '"');
            header('Cache-Control: max-age=0');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($data));
            readfile($data);
            // unlink($data);
            exit;
        } else {
            data_dump($this->input->getPost());
        }
    }

    /**
     * @return file
     * ----------------------------------------------------
     * Action Download Excel
     */
    public function actionUploadExcel()
    {       
        [
        'data' => $data, 'status' => $status, 'message' => $message,
        ] = $this->serviceExcel->uploadExcel($this->input->getPost(), $this->input->getFile('file_template'));
        
        return $status ? $this->responseSuccess(array('data' => $data), $message) : $this->responseError($data, $message);
    }

    /**
     * @return json
     * ----------------------------------------------------
     * Loaded Ajax Inquiry
     */
    public function getInvalidData()
    {
        ['data'      => $data] = $this->serviceExcel->getInvalidData($this->request->getPost());

        return $this->responseSuccess($data,'Successfully loaded data');
    }

    /**
     * @return json
     * ----------------------------------------------------
     * Loaded Ajax Inquiry
     */
    public function actionSubmitExcelTemplate()
    {
        ['data'      => $data, 'message' => $message] = $this->serviceExcel->submitExcelTemplate($this->request->getPost());

        return $this->responseSuccess($data, $message);
    }

    /**
     * @return json
     * ----------------------------------------------------
     * Action Get Options
     */
    public function getDetailAllowancesDeductions()
    {
        $company_id = $this->input->getPost('company_id');
        $work_unit_id = $this->input->getPost('work_unit_id');
        $employee_group = $this->input->getPost('employee_group');
        $result = json_decode(json_encode($this->service->getAllowanceDeductionByKey($company_id, $work_unit_id, $employee_group)), true);
        return $result['status'] ? $this->responseSuccess($result['data'], $result['message']) : $this->responseError($result['data'], $result['message']);
    }

    /**
     * @return json
     * ----------------------------------------------------
     * Action Get Options
     */
    public function getDetailAllowancesDeductionsSalary()
    {
        $basic_salary_id = $this->input->getPost('basic_salary_id');
        $result = json_decode(json_encode($this->service->getAllowanceDeductionSalaryByKey($basic_salary_id)), true);
        return $result['status'] ? $this->responseSuccess($result['data'], $result['message']) : $this->responseError($result['data'], $result['message']);
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
        ] = $this->serviceOptionsEmployeee->getOptions($this->input->getGet());

        return $status ? $this->responseSuccess($data, $message) : $this->responseError($data, $message);
    }

    /**
     * @return json
     * ----------------------------------------------------
     * Action Get Options
     */
    public function getOptionsCostCenter()
    {
        [
        'data' => $data, 'status' => $status, 'message' => $message,
        ] = $this->serviceOptionsCostCenter->getOptions($this->input->getGet());

        return $status ? $this->responseSuccess($data, $message) : $this->responseError($data, $message);
    }

    /**
     * @return json
     * ----------------------------------------------------
     * Action Get Options
     */
    public function getOptionsSytem()
    {
        [
        'data' => $data, 'status' => $status, 'message' => $message,
        ] = $this->serviceOptionsSystem->getOptions($this->input->getGet());

        return $status ? $this->responseSuccess($data, $message) : $this->responseError($data, $message);
    }

    /**
     * @return json
     * ----------------------------------------------------
     * Action Get Options
     */
    public function getOptionsEmployeeContract()
    {
        [
        'data' => $data, 'status' => $status, 'message' => $message,
        ] = $this->serviceOptionEmployeeContract->getOptions($this->input->getGet());

        return $status ? $this->responseSuccess($data, $message) : $this->responseError($data, $message);
    }
}