<?php

namespace App\Controllers\Master;

use App\Core\CoreController;
use App\Services\Master\Compensation\CompensationExcelService;
use App\Services\Master\MasterCompensationService;
use App\Services\Options\OptionsAreaService;
use App\Services\Options\OptionsRoleService;
use App\Services\Options\OptionsCompanyService;
use App\Services\Options\OptionsEmployeeService;

class MasterCompensationController extends CoreController
{
    protected $service;
    protected $function_id = 310;
    protected $serviceOptionsArea;
    protected $serviceOptionsRole;
    protected $serviceOptionsCompany;
    protected $serviceOptionsEmployee;
    protected $uploadService;


    public function __construct()
    {
        parent::__construct();
        $this->service = new MasterCompensationService();
        $this->serviceOptionsArea = new OptionsAreaService();
        $this->serviceOptionsCompany = new OptionsCompanyService();
        $this->serviceOptionsRole = new OptionsRoleService();
        $this->serviceOptionsEmployee = new OptionsEmployeeService();
        $this->uploadService = new CompensationExcelService();
    }
    /**
     * @return view
     * ----------------------------------------------------
     * Page View Inquiry
     */
    public function index()
    {
        $data = get_title($this->menu, 'master_kompensasi');
        $data['jsapp'] = array_merge(
            $this->defaultJs,
            array(
                'shared/datatable',
                'master/compensation/inquiry',
                'shared/common_inquiry',
                'master/compensation/utility'
            )
        );



        return $this->loadView('master/compensation/index', $data);
    }

    /**
     * @return json
     * ----------------------------------------------------
     * Action Get Options
     */
    // public function getOptionsCompany()
    // {
    //     [
    //         'data' => $data, 'status' => $status, 'message' => $message
    //     ] = $this->service->getOptionsCompany($this->request->getGet());

    //     return $status ? $this->responseSuccess($data, $message) : $this->responseError($data, $message);
    // }

    public function getOptionsCompany()
    {
        [
            'data' => $data, 'status' => $status, 'message' => $message,
        ] = $this->serviceOptionsCompany->getOptions($this->input->getGet());

        return $status ? $this->responseSuccess($data, $message) : $this->responseError($data, $message);
    }

    // public function getOptionsArea()
    // {
    //     [
    //         'data' => $data, 'status' => $status, 'message' => $message
    //     ] = $this->service->getOptionsArea($this->request->getGet());

    //     return $status ? $this->responseSuccess($data, $message) : $this->responseError($data, $message);
    // }

    public function getOptionsArea()
    {
        [
            'data' => $data, 'status' => $status, 'message' => $message,
        ] = $this->serviceOptionsArea->getOptions($this->input->getGet());

        return $status ? $this->responseSuccess($data, $message) : $this->responseError($data, $message);
    }

    public function getOptionsRole()
    {
        [
            'data' => $data, 'status' => $status, 'message' => $message,
        ] = $this->serviceOptionsRole->getOptions($this->input->getGet());

        return $status ? $this->responseSuccess($data, $message) : $this->responseError($data, $message);
    }

    // public function getOptionsEmployee()
    // {
    //     [
    //         'data' => $data, 'status' => $status, 'message' => $message
    //     ] = $this->service->getOptionsEmployee($this->request->getGet());

    //     return $status ? $this->responseSuccess($data, $message) : $this->responseError($data, $message);
    // }

    public function getOptionsEmployee()
    {
        [
            'data' => $data, 'status' => $status, 'message' => $message,
        ] = $this->serviceOptionsEmployee->getOptions($this->input->getGet());

        return $status ? $this->responseSuccess($data, $message) : $this->responseError($data, $message);
    }

    public function getOptionsCompensationType()
    {
        [
            'data' => $data, 'status' => $status, 'message' => $message
        ] = $this->service->getOptionsCompensationType($this->request->getGet());

        return $status ? $this->responseSuccess($data, $message) : $this->responseError($data, $message);
    }

    /**
     * @return json
     * ----------------------------------------------------
     * Loaded Ajax Inquiry
     */
    public function getDataTable()
    {
        ['data'      => $data] = $this->service->datatable($this->request->getPost());

        return $this->responseSuccess($data, 'Successfully loaded data');
    }

    public function create()
    {
        $data = get_title($this->menu, 'master_kompensasi');
        $data['jsapp'] = array_merge(
            $this->defaultJs,
            array('master/compensation/create')
        );

        return $this->loadView('master/compensation/form', $data);
    }

    public function actionCreate()
    {
        [
            'data' => $data, 'status' => $status, 'message' => $message,
        ] = $this->service->create($this->request->getPost());

        if ($status === false) return $this->responseError($data, $message);

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
     * @return view
     * ----------------------------------------------------
     * Page View Detail
     */
    public function id($compensation_id)
    {
        $data = get_title($this->menu, 'master_kompensasi');
        $data['jsapp'] = array_merge(
            $this->defaultJs,
            array(
                // 'master/compensation/id',
                // 'master/compensation/utility',
                'shared/datatable',
                'shared/payroll_inquiry',
            )
        );
        $data['compensation'] = $this->service->getMasterCompensation($compensation_id)['data'];
        $data['function_id'] = (string) $this->function_id;
        $data['refference_id'] = $compensation_id;
        // echo '<pre>';
        // print_r($data);
        // die();

        return $this->loadView('master/compensation/id', $data);
    }

    public function edit($compensation_id)
    {
        $data = get_title($this->menu, 'master_kompensasi');
        $data['jsapp'] = array_merge(
            $this->defaultJs,
            array(
                'master/compensation/update',
                'master/compensation/utility',
            )
        );
        $data['compensation'] = $this->service->getMasterCompensation($compensation_id)['data'];
        //     echo '<pre>';
        //     print_r($data);
        // die();

        return $this->loadView('master/compensation/form', $data);
    }

    /**
     * @return json
     * ----------------------------------------------------
     * for table detail history
     */

    public function getTableDetail()
    {
        ['data' => $data] = $this->service->datatable($this->request->getPost());

        return $this->responseSuccess($data, 'Successfully loaded data');
    }

    /**
     * @return view
     * ----------------------------------------------------
     * Page View Upload
     */

    public function upload()
    {
        $data = get_title($this->menu, 'master_kompensasi');
        $data['jsapp'] = array_merge(
            $this->defaultJs,
            array(
                'master/compensation/upload',
                'shared/datatable',
                'master/compensation/utility'
            )
        );
        return $this->loadView('master/compensation/upload', $data);
    }

    public function actionDownload()
    {
        [
            'data' => $data, 'status' => $status, 'message' => $message,
        ] = $this->service->actionDownload($this->input->getPost());

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


    public function uploadTemporary()
    {
        ['data' => $data, 'status' => $status, 'message' => $message,] =
            $this->uploadService->actionUpload($this->input->getPost(), $this->input->getFile('file_template'));

        return $status ? $this->responseSuccess(array('data' => $data), $message) : $this->responseError($data, $message);
    }

    /**
     * @return json
     * ----------------------------------------------------
     * Loaded Ajax Inquiry
    */

    public function showTemporary()
    {
        ['data' => $data] = $this->uploadService->uploadDataTable($this->request->getPost());

        return $this->responseSuccess($data, 'Successfully loaded data');
    }

        /**
     * @return json
     * ----------------------------------------------------
     * Loaded Ajax Inquiry
     */
    public function getInvalidData()
    {
        ['data' => $data] = $this->uploadService->getInvalidData($this->request->getPost());

        return $this->responseSuccess($data,'Successfully loaded data');
    }

    public function actionSubmitExcel()
    {
        ['data' => $data, 'message' => $message] = $this->uploadService->actionSubmitExcel($this->request->getPost());

        return $this->responseSuccess($data, $message);
    }

    public function actionDownloadTemplate()
    {       
        [
        'data' => $data, 'status' => $status, 'message' => $message,
        ] = $this->uploadService->actionDownloadTemplate($this->input->getPost());
        
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

    
}
