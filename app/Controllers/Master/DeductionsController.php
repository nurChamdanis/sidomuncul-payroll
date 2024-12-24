<?php

namespace App\Controllers\Master;

use App\Core\CoreController;
use App\Services\Master\Deductions\DeductionExcelService;
use App\Services\Master\Deductions\DeductionService;
use App\Services\Master\Deductions\DeductionServiceArea;
use App\Services\Master\Deductions\DeductionServiceAreaGrup;
use App\Services\Master\Deductions\DeductionServicePayrollRules;
use App\Services\Master\GlAccounts\GlAccountService;
use App\Services\Master\MasterSystemService;
use App\Services\Options\OptionsAreaGroupService;
use App\Services\Options\OptionsAreaService;
use App\Services\Options\OptionsCompanyService;
use App\Services\Options\OptionsGLAccountService;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @created_dt 2024-05-27 
 * --------------------------------
 * @modified luthfi.aziz@arkamaya.co.id on May 2024
 */
class DeductionsController extends CoreController
{
    protected $function_id = 304;
    protected $service;
    protected $serviceExcel;
    protected $serviceOptionsCompany;
    protected $serviceOptionsArea;
    protected $serviceOptionsAreaGroup;
    protected $serviceOptionsGLAccount;
    protected $serviceArea;
    protected $serviceAreaGrup;
    protected $serviceDeductionPayrollRules;
    protected $serviceSystem;
    protected $serviceGLAccount;

    public function __construct()
    {
        parent::__construct();
        $this->service = new DeductionService();
        $this->serviceExcel = new DeductionExcelService();
        $this->serviceOptionsCompany = new OptionsCompanyService();
        $this->serviceOptionsArea = new OptionsAreaService();
        $this->serviceOptionsAreaGroup = new OptionsAreaGroupService();
        $this->serviceOptionsGLAccount = new OptionsGLAccountService();
        $this->serviceArea  = new DeductionServiceArea();
        $this->serviceAreaGrup = new DeductionServiceAreaGrup();
        $this->serviceDeductionPayrollRules  = new DeductionServicePayrollRules();
        $this->serviceSystem = new MasterSystemService();
        $this->serviceGLAccount = new GlAccountService();
    }

    /**
     * @return view
     * ----------------------------------------------------
     * Page View Inquiry
     */
    public function index()
    {
        $data = get_title($this->menu, 'master_potongan');
        $data['jsapp']  = array_merge(
            $this->defaultJs,
            array(
                'shared/common_inquiry',
                'shared/datatable',
                'master/deductions/inquiry'
            )
        );

        return $this->loadView('master/deductions/index', $data);
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
     * Page View Detail
     */
    public function id($deduction_id)
    {
        $data           = get_title($this->menu, 'master_potongan');
        $data['jsapp']  = array_merge(
            $this->defaultJs,
            array(
                'master/deductions/id',
                'master/deductions/utility',
                'shared/datatable',
                'shared/payroll_inquiry',
            )
        );

        $data['deduction'] = $this->service->getByKey($deduction_id)['data'];
        $glId = isset($data['deduction']->gl_id) ? $data['deduction']->gl_id : '';
        $data['glaccount'] = $this->serviceGLAccount->getByKey($glId)['data'];
        $data['deduction_area'] = array_map(function($item){
            return $item->area_id;
        }, $this->serviceArea->getByKey($deduction_id)['data']);

        $data['deduction_area_group'] = array_map(function($item){
            return $item->area_id;
        }, $this->serviceAreaGrup->getByKey($deduction_id)['data']); 

        $data['deduction_payroll_rules'] = array_map(function($item){
            return $item->rules_id;
        }, $this->serviceDeductionPayrollRules->getByKey($deduction_id)['data']);
        
        $service_data = $this->serviceArea->getAll();
        $data['area'] = $service_data['status'] ? $service_data['data'] : array();
        
        $service_data     = $this->serviceAreaGrup->getAll();
        $data['areaGrup'] = $service_data['status'] ? $service_data['data'] : array();
        
        $service_data     = $this->serviceDeductionPayrollRules->getAll();
        $data['areaPayrollRules'] = $service_data['status'] ? $service_data['data'] : array();
        
        $service_data            = $this->serviceSystem->getCalculationType();
        $data['calculationType'] = $service_data['status'] ? $service_data['data'] : array();
        
        $service_data            = $this->serviceSystem->getCalculationMode();
        $data['calculationMode'] = $service_data['status'] ? $service_data['data'] : array();
        
        $data['function_id']    = (string) $this->function_id;
        $data['refference_id']  = $deduction_id;
        
        return $this->loadView('master/deductions/id', $data);
    }

    /**
     * @return view
     * ----------------------------------------------------
     * Page View Create
     */
    public function create()
    {
        $data           = get_title($this->menu, 'master_potongan');
        $data['jsapp']  = array_merge(
            $this->defaultJs,
            array(
                'master/deductions/create',
                'master/deductions/utility',
            )
        );
        
        $service_data = $this->serviceArea->getAll();
        $data['area'] = $service_data['status'] ? $service_data['data'] : array();
        
        $service_data     = $this->serviceAreaGrup->getAll();
        $data['areaGrup'] = $service_data['status'] ? $service_data['data'] : array();
        
        $service_data               = $this->serviceDeductionPayrollRules->getAll();
        $data['areaPayrollRules']   = $service_data['status'] ? $service_data['data'] : array();
        
        $service_data            = $this->serviceSystem->getCalculationType();
        $data['calculationType'] = $service_data['status'] ? $service_data['data'] : array();
        
        $service_data            = $this->serviceSystem->getCalculationMode();
        $data['calculationMode'] = $service_data['status'] ? $service_data['data'] : array();
        
        return $this->loadView('master/deductions/form', $data);
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
     * @return view
     * ----------------------------------------------------
     * Page View Update
     */
    public function edit($deduction_id)
    {
        $data           = get_title($this->menu, 'master_potongan');
        $data['jsapp']  = array_merge(
            $this->defaultJs,
            array(
                'master/deductions/update',
                'master/deductions/utility',
            )
        );
        
        $data['deduction'] = $this->service->getByKey($deduction_id)['data'];
        $glId = isset($data['deduction']->gl_id) ? $data['deduction']->gl_id : '';
        $data['glaccount'] = $this->serviceGLAccount->getByKey($glId)['data'];
        $data['deduction_area'] = array_map(function($item){
            return $item->area_id;
        }, $this->serviceArea->getByKey($deduction_id)['data']);

        $data['deduction_area_group'] = array_map(function($item){
            return $item->area_id;
        }, $this->serviceAreaGrup->getByKey($deduction_id)['data']); 

        $data['deduction_payroll_rules'] = array_map(function($item){
            return $item->rules_id;
        }, $this->serviceDeductionPayrollRules->getByKey($deduction_id)['data']);
        
        $service_data = $this->serviceArea->getAll();
        $data['area'] = $service_data['status'] ? $service_data['data'] : array();
        
        $service_data     = $this->serviceAreaGrup->getAll();
        $data['areaGrup'] = $service_data['status'] ? $service_data['data'] : array();
        
        $service_data     = $this->serviceDeductionPayrollRules->getAll();
        $data['areaPayrollRules'] = $service_data['status'] ? $service_data['data'] : array();
        
        $service_data            = $this->serviceSystem->getCalculationType();
        $data['calculationType'] = $service_data['status'] ? $service_data['data'] : array();
        
        $service_data            = $this->serviceSystem->getCalculationMode();
        $data['calculationMode'] = $service_data['status'] ? $service_data['data'] : array();

        return $this->loadView('master/deductions/form', $data);
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
    public function getOptionsAreaGroup()
    {
        [
        'data' => $data, 'status' => $status, 'message' => $message,
        ] = $this->serviceOptionsAreaGroup->getOptions($this->input->getGet());

        return $status ? $this->responseSuccess($data, $message) : $this->responseError($data, $message);
    }
    
    /**
     * @return json
     * ----------------------------------------------------
     * Action Get Options
     */
    public function getOptionsGLAccount()
    {
        [
        'data' => $data, 'status' => $status, 'message' => $message,
        ] = $this->serviceOptionsGLAccount->getOptions($this->input->getGet());

        return $status ? $this->responseSuccess($data, $message) : $this->responseError($data, $message);
    }
    
    /**
     * @return json
     * ----------------------------------------------------
     * Action Get All Area 
     */
    public function getAllArea()
    {
        [
        'data' => $data, 'status' => $status, 'message' => $message,
        ] = $this->serviceArea->getAll($this->input->getGet());

        return $status ? $this->responseSuccess(array('data' => $data), $message) : $this->responseError($data, $message);
    }

    /**
     * @return json
     * ----------------------------------------------------
     * Action Get All Area Grup
     */
    public function getAllAreaGrup()
    {
        [
        'data' => $data, 'status' => $status, 'message' => $message,
        ] = $this->serviceAreaGrup->getAll($this->input->getGet());

        return $status ? $this->responseSuccess(array('data' => $data), $message) : $this->responseError($data, $message);
    }

    /**
     * @return json
     * ----------------------------------------------------
     * Action Get Options
     */
    public function getAllPayrollRules()
    {
        [
        'data' => $data, 'status' => $status, 'message' => $message,
        ] = $this->serviceDeductionPayrollRules->getAll($this->input->getGet());

        return $status ? $this->responseSuccess(array('data' => $data), $message) : $this->responseError($data, $message);
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
     * @return view
     * ----------------------------------------------------
     * Page View Upload
     */
    public function index_upload()
    {
        $data = get_title($this->menu, 'master_potongan');
        $data['jsapp']  = array_merge(
            $this->defaultJs,
            array(
                'shared/datatable',
                'master/deductions/inquiry_upload'
            )
        );

        return $this->loadView('master/deductions/index_upload', $data);
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
    public function getUploadDataTable()
    {
        ['data'      => $data] = $this->serviceExcel->datatable_uploaded($this->request->getPost());

        return $this->responseSuccess($data,'Successfully loaded data');
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
}