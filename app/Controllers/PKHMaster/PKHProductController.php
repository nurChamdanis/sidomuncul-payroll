<?php

namespace App\Controllers\PKHMaster;

use App\Core\CoreController; 
use App\Services\Master\PKH\PKHProductService;
use App\Services\Options\OptionsAreaService;
use App\Services\Options\OptionsRoleService;
use App\Services\Options\OptionsCompanyService; 
use App\Services\Options\OptionsPiecesService; 

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @created_dt 2024-05-27 
 * --------------------------------
 * @modified luthfi.aziz@arkamaya.co.id on May 2024
 */
class PKHProductController extends CoreController
{
    protected $function_id = 801;
    protected $service;
    protected $serviceExcel;
    protected $serviceOptionsPieces;
    protected $serviceOptionsCompany;
    protected $serviceOptionsArea;
    protected $serviceOptionsRole;
    protected $serviceOptionsAreaGroup;
    protected $serviceOptionsGLAccount;
    protected $serviceArea;
    protected $serviceAreaGrup;
    protected $serviceAllowancePayrollRules;
    protected $serviceSystem;
    protected $serviceGLAccount;

    public function __construct()
    {
        parent::__construct(); 
        $this->service = new PKHProductService();
        $this->serviceOptionsPieces = new OptionsPiecesService();
        $this->serviceOptionsCompany = new OptionsCompanyService();
        $this->serviceOptionsArea = new OptionsAreaService();
        $this->serviceOptionsRole = new OptionsRoleService(); 
    }

    /**
     * @return view
     * ----------------------------------------------------
     * Page View Inquiry
     */
    public function index(){
        $data = get_title($this->menu, 'pkh_product');
        $data['jsapp']  = array_merge(
            $this->defaultJs,
            array(
                'shared/common_inquiry',
                'shared/datatable',
                'pkh/product/inquiry'
            )
        );

        return $this->loadView('pkh/product/index', $data);
    }

    /**
     * @return view
     * ----------------------------------------------------
     * Page View Inquiry
     */
    public function create()
    { 
        $data = get_title($this->menu, 'pkh_product');
        $data['jsapp']  = array_merge(
            $this->defaultJs,
            array(
                'pkh/product/create',
                'pkh/product/utility'
            )
        ); 
        return $this->loadView('pkh/product/form', $data);
    }


    /**
     * @return json
     * ----------------------------------------------------
     * Action Get Options
     */
    public function getOptionsPieces()
    {
        [
            'data' => $data, 'status' => $status, 'message' => $message,
        ] = $this->serviceOptionsPieces->getOptions($this->input->getGet());

        return $status ? $this->responseSuccess($data, $message) : $this->responseError($data, $message);
    }


    /**
     * @return json
     * ----------------------------------------------------
     * Action Get Options
     */
    public function getOptionsCompany(){
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
    public function getOptionsArea() {
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
    public function getOptionsRole() {
        [
            'data' => $data, 'status' => $status, 'message' => $message,
        ] = $this->serviceOptionsRole->getOptions($this->input->getGet());

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

        if ($status === false) return $this->responseError($data, $message);

        return $this->responseSuccess($data, $message);
    }


}