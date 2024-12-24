<?php

namespace App\Controllers\Settings;

use App\Core\CoreController;
use App\Services\Master\MasterSystemService;

/**
 * @author misbah@arkamaya.co.id
 * @since 2017-02-06 
 * --------------------------------
 * @modified luthfi.aziz@arkamaya.co.id on May 2024
 */
class MasterSystemController extends CoreController
{
    protected $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = new MasterSystemService();
    }

    /**
     * @return view
     * ----------------------------------------------------
     * Page View Inquiry
     */
    public function index()
    {
        $data = get_title($this->menu, 'master_system');
        $data['jsapp'] = array('shared/datatable', 'master/system/inquiry');
        
        return $this->loadView('master/system/index', $data);
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
    public function id($system_type, $system_code)
    {
        $data           = get_title($this->menu, 'master_system');
        $data['jsapp']  = array_merge(
            $this->defaultJs,
            array(
                'master/system/id'
            )
        );
        $data['system'] = $this->service->getMasterSystem($system_type, $system_code)['data'];

        return $this->loadView('master/system/id', $data);
    }

    /**
     * @return view
     * ----------------------------------------------------
     * Page View Create
     */
    public function create()
    {
        $data           = get_title($this->menu, 'master_system');
        $data['jsapp']  = array_merge(
            $this->defaultJs,
            array(
                'master/system/create',
                'master/system/utility',
            )
        );
        
        return $this->loadView('master/system/form', $data);
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
    public function edit($system_type, $system_code)
    {
        $data           = get_title($this->menu, 'master_system');
        $data['jsapp']  = array_merge(
            $this->defaultJs,
            array(
                'master/system/update',
                'master/system/utility',
            )
        );
        $data['system'] = $this->service->getMasterSystem($system_type, $system_code)['data'];

        return $this->loadView('master/system/form', $data);
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
     * Action Get Options
     */
    public function getOptions()
    {
        [
        'data' => $data, 'status' => $status, 'message' => $message,
        ] = $this->service->getOptions($this->input->getGet());

        return $status ? $this->responseSuccess($data, $message) : $this->responseError($data, $message);
    }
}