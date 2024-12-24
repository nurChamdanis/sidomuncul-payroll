<?php

namespace App\Services\Master;

/** 
 * @author fernanda.rizqi@arkamaya.co.id
 * @since May 2024
 */

use App\Entities\Master\MasterCompensationEntity;
use App\Helpers\Datatable;
use App\Repositories\Master\MasterCompensationRepository;
use App\Services\BaseService;
use App\Services\Logs\PayrollLogService;
use App\Services\Master\Compensation\CompensationDownloadService;
use App\Services\Master\Compensation\CompensationUploadService;
use App\Repositories\Master\CompanyRepository;
use App\Repositories\Master\MasterSystemRepository;
use PhpParser\Node\Stmt\TryCatch;

class MasterCompensationService extends BaseService
{
    protected mixed $repository;
    protected $downloadService;
    protected $uploadService;
    protected $companyRepository;
    protected $functionId = 310;
    protected $payrollLogService;
    protected mixed $systemRepository;


    public function __construct()
    {
        parent::__construct();
        $this->repository = new MasterCompensationRepository();
        $this->downloadService = new CompensationDownloadService();
        $this->uploadService = new CompensationUploadService();
        $this->payrollLogService = new PayrollLogService();
        $this->companyRepository = new CompanyRepository();
        $this->systemRepository = new MasterSystemRepository();
    }

    /**
     * @var array $payload
     * @return array
     * ----------------------------------------------------
     * groups : get options
     * desc : get options data for 5 filter criteria
     */

    public function getOptionsCompany(?array $params): array
    {
        $page = isset($params['page']) ? $params['page'] : 1;
        $search = isset($params['search']) ? $params['search'] : '';

        try {
            $options = $this->repository->getOptionsCompany($page, $search);
        } catch (\Exception $e) {
            return $this->dataError(
                log: true,
                code: 500,
                data: array(),
                message: $e->getMessage(),
            );
        }

        return $this->dataSuccess(
            log: true,
            code: 200,
            data: $options,
            message: 'Successfully Loaded Options Data',
        );
    }

    public function getOptionsArea(?array $params): array
    {
        $page = isset($params['page']) ? $params['page'] : 1;
        $search = isset($params['search']) ? $params['search'] : '';

        try {
            $options = $this->repository->getOptionsArea($page, $search);
        } catch (\Exception $e) {
            return $this->dataError(
                log: true,
                code: 500,
                data: array(),
                message: $e->getMessage(),
            );
        }

        return $this->dataSuccess(
            log: true,
            code: 200,
            data: $options,
            message: 'Successfully Loaded Options Data',
        );
    }

    public function getOptionsRole(?array $params): array
    {
        $page = isset($params['page']) ? $params['page'] : 1;
        $search = isset($params['search']) ? $params['search'] : '';

        try {
            $options = $this->repository->getOptionsRole($page, $search);
        } catch (\Exception $e) {
            return $this->dataError(
                log: true,
                code: 500,
                data: array(),
                message: $e->getMessage(),
            );
        }

        return $this->dataSuccess(
            log: true,
            code: 200,
            data: $options,
            message: 'Successfully Loaded Options Data',
        );
    }

    public function getOptionsEmployee(?array $params): array
    {
        $page = isset($params['page']) ? $params['page'] : 1;
        $search = isset($params['search']) ? $params['search'] : '';

        try {
            $options = $this->repository->getOptionsEmployee($page, $search);
        } catch (\Exception $e) {
            return $this->dataError(
                log: true,
                code: 500,
                data: array(),
                message: $e->getMessage(),
            );
        }

        return $this->dataSuccess(
            log: true,
            code: 200,
            data: $options,
            message: 'Successfully Loaded Options Data',
        );
    }

    public function getOptionsCompensationType(?array $params): array
    {
        $page = isset($params['page']) ? $params['page'] : 1;
        $search = isset($params['search']) ? $params['search'] : '';

        try {
            $options = $this->repository->getOptionsCompensationType($page, $search);
            // data_dump($options);
        } catch (\Exception $e) {
            return $this->dataError(
                log: true,
                code: 500,
                data: array(),
                message: $e->getMessage(),
            );
        }

        return $this->dataSuccess(
            log: true,
            code: 200,
            data: $options,
            message: 'Successfully Loaded Options Data',
        );
    }

    /**
     * @var array $payload
     * @return array
     * ----------------------------------------------------
     * name : datatable()
     * desc : Service to loaded datatable
     */

    public function datatable(?array $payload): array
    {
        $this->serviceAction = '[MASTER_KOMPENSASI][INQUIRY]';

        //filter datatable

        // $orderBy = "created_dt, changed_by, changed_dt";

        // echo '<pre>';
        // print_r($payload);
        // print('ini payload di servis');
        // die();

        $searchFilters = function () use ($payload) {

            $filters = array();
            if (isset($payload['company_id'])) {
                $filters['tb_r_payroll_compensation.company_id'] = $payload['company_id'];
            }
            if (isset($payload['work_unit_id'])) {
                $filters['tb_r_payroll_compensation.work_unit_id'] = $payload['work_unit_id'];
            }
            if (isset($payload['role_id'])) {
                $filters['tb_r_payroll_compensation.role_id'] = $payload['role_id'];
            }
            if (isset($payload['employee_id'])) {
                $filters['tb_r_payroll_compensation.employee_id'] = $payload['employee_id'];
            }
            if (isset($payload['compensationType'])) {
                $filters['tb_m_system.compensation_type'] = $payload['compensationType'];
            }
            if (isset($payload['valid_from'])) {
                $filters['valid_from'] = $payload['valid_from'];
            }
            if (isset($payload['valid_to'])) {
                $filters['valid_to'] = $payload['valid_to'];
            }
            return $filters;
        };


        // Configure datatable settings
        // --------------------------------

        function labelDateCustom($val)
        {
            if (!empty($val) && $val != '-') {

                list($year, $month) = explode('-', $val);

                if (is_numeric($month) && is_numeric($year) && $month >= 1 && $month <= 12) {

                    $timestamp = mktime(0, 0, 0, $month, 1, $year);

                    $data = date('F Y', $timestamp);

                    $html = "<div>
                                <div class='custom_label'>{$data}</div>
                            </div>";
                    return $html;
                } else {
                    return "Invalid date format";
                }
            } else {
                return "-";
            }
        }

        $formattedFields = function ($item) {
            $companyName = isEmpty($item->company_name);
            $areaName = isEmpty($item->name);
            $roleName = isEmpty($item->role_name);
            $employeeNoReg =  isEmpty($item->no_reg);
            $employeeName = isEmpty($item->employee_name);
            $period = labelDateCustom(isEmpty($item->period));
            $compensationType = isEmpty($item->system_value_txt);
            $totalCompensation = number($this->decrypt($item->total_compensation));
            $createdBy = isEmpty($item->created_by);
            $createdDt = labelDate(isEmpty($item->created_dt));
            $changedBy = isEmpty($item->changed_by);
            $changedDt = labelDate(isEmpty($item->changed_dt));

            return [
                "
                <div class='checkbox checkbox-custom'>
                    <input type='checkbox' class='compensation' id='compensation_{$item->compensation_id}' value='{$item->compensation_id}' onClick=\"selfChecked('checkAll', 'btn_edit_inquiry', 'btn_delete_inquiry', 'compensation')\">
                    <label class='checkbox-inline' for='compensation_{$item->compensation_id}'></label>
                </div'>
                ",
                "
                <div class='text-left'>
                    {$companyName}
                </div'>
                ",
                "
                <div class='text-left'>
                    {$areaName}
                </div'>
                ",
                "
                <div class='text-left'>
                    {$roleName}
                </div'>
                ",
                "
                <div class='text-center'>
                    {$employeeNoReg}
                </div'>
                ",
                "
                <div class='text-left'>
                    {$employeeName}
                </div'>
                ",
                "
                <div class='text-center'>
                    {$period}
                </div'>
                ",
                "
                <div class='text-center'>
                    {$compensationType}
                </div'>
                ",
                "
                <div class='text-right'>
                    {$totalCompensation}
                </div'>
                ",
                "
                <div class='text-left'>
                    {$createdBy}
                </div'>
                ",
                "
                <div class='text-center'>
                    {$createdDt}
                </div'>
                ",
                "
                <div class='text-left'>
                    {$changedBy}
                </div'>
                ",
                "
                <div class='text-center'>
                    {$changedDt}
                </div'>
                "
            ];
        };

        $order      = $payload['order'];
        $column     = $payload['columns'];
        $id_cols    = $order[0]['column'];

        $orderBy = " created_by, created_dt, changed_by, changed_dt, ";
        if (isset($column[$id_cols]['name'])) {
            $orderBy .= $column[$id_cols]['name'] . " " . $order[0]['dir'];
        }

        // Instance datatable class
        // --------------------------------
        $table = new Datatable($this->repository, $payload);
        $table->setFilters($searchFilters);
        $table->setOrderBy($orderBy);

        return $this->dataSuccess(
            code: 200,
            data: $table->getRows(fn ($items) => array_map($formattedFields, $items)),
            message: 'Successfully loaded datatable system data.',
        );
    }

    /**
     * @var array $params
     * @return array
     * ----------------------------------------------------
     * name: create($params)
     * desc: Service to create new system data
     */
    public function create(?array $params): array
    {
        $this->serviceAction = '[MASTER_KOMPENSASI][CREATE]';

        $repository = $this->repository;
        $error      = null;

        $result     = queryTransaction(function () use ($params, $repository) {

            $keysExclude = ['old_compensation_id', 'old_company_id', 'old_work_unit_id', 'old_employee_id', 'old_compensation_type', 'old_period', 'old_total_compensation', 'old_description'];
            // print_r($params);
            // die();

            // check if data exist
            $isExists = $repository->compensationCheck($params['employee_id'], $params['compensation_type'], $params['period']);

            if ($isExists > 0) {
                throw new \Exception('Data already exists in our system, please send another request.');
            }

            // change $params['period'] from mm/yyyy to yyyy-mm
            list($month, $year) = explode('/', $params['period']);
            $params['period'] = $year . '-' . $month;
            $params['total_compensation'] = $this->encrypt(decimalvalue($params['total_compensation']));
            $entity = new MasterCompensationEntity(array_exclude($params, $keysExclude));
            $entity->created_dt = date('Y-m-d H:i:s');
            $entity->created_by = $this->S_EMPLOYEE_NAME;

            // $repository->save($entity->toArray());
            $compensationResult = $repository->save($entity->toArray());

            return $compensationResult;
        }, $error);


        if ($result === false) {
            return $this->dataError(
                log: true,
                code: 500,
                data: null,
                message: $error,
            );
        }


        $this->payrollLogService->create(array(
            'function_id' => $this->functionId,
            'refference_id' => (string) $result['id'],
            'data_before' => '',
            'data_after' => '',
            'history_details' => $this->S_EMPLOYEE_NAME . '--> has created new compensation data'
        ));


        return $this->dataSuccess(
            log: true,
            code: 201,
            data: array("redirect_link" => "master_kompensasi/id/" . $result['id']),
            // data: null,
            message: 'Successfully Created System',
        );
    }

    public function update(?array $params): array
    {
        $this->serviceAction = '[MASTER_COMPENSATION][UPDATE]';

        $compensation_id = $params['old_compensation_id'];
        $repository = $this->repository;
        $error      = null;

        // data_dump($params);
        // die();
        $data = $this->repository->findByOtherKey(array(
            'compensation_id' => $compensation_id
        ));

        $data_before = array(
            "company_name" => $data->company_name,
            "work_unit_name" => $data->name,
            "employee_name" => $data->employee_name,
            "compensation_type" => $data->system_value_txt,
            "period" => $data->period,
            "total_compensation" => $this->decrypt($data->total_compensation),
            "compensation_description" => $data->compensation_description
        );

        $result     = queryTransaction(function () use ($params, $repository) {
            $key    = array(
                'compensation_id' => $params['old_compensation_id'],
            );

            $isExists = $repository->compensationCheck($params['old_employee_id'], $params['compensation_type'], $params['period']);

            // using check data using isexists variable above
            // this check employee_id, compensation_type, period
            // if data exists other than itself, then throw exception
            // but if data exists and the data is itself or if data is not exist, then continue to update

            if ($isExists > 0) {
                $checkData = $repository->compensationRow($params['old_employee_id'], $params['compensation_type'], $params['period']);
                if ($checkData->compensation_id != $params['old_compensation_id']) {
                    throw new \Exception('Data already exists in our system, please send another request.');
                }
            }

            $keysExclude = ['old_compensation_id', 'old_company_id', 'old_work_unit_id', 'old_employee_id', 'old_compensation_type', 'old_period', 'old_total_compensation', 'old_description'];
            $params['total_compensation'] = $this->encrypt(decimalvalue($params['total_compensation']));
            $entity = new MasterCompensationEntity(array_exclude($params, $keysExclude));
            $entity->changed_dt  = date('Y-m-d H:i:s');
            $entity->changed_by  = $this->S_EMPLOYEE_NAME;

            // data_dump($entity->toArray());
            // die();

            return $repository->update($entity->toArray(), $key);
        }, $error);




        // $data_after= array_exclude($result, ['changed_dt', 'changed_by']);
        // data_dump($data_after);

        $data_update = $this->repository->findByOtherKey(array(
            'compensation_id' => $compensation_id
        ));

        // data_dump(json_encode($result));
        // die();

        $data_after = array(
            "company_name" => $data_update->company_name,
            "work_unit_name" => $data_update->name,
            "employee_name" => $data_update->employee_name,
            "compensation_type" => $data_update->system_value_txt,
            "period" => $data_update->period,
            "total_compensation" => $this->decrypt($data_update->total_compensation),
            "compensation_description" => $data_update->compensation_description
        );



        $this->payrollLogService->create(array(
            'function_id' => $this->functionId,
            'refference_id' => $compensation_id,
            'data_before' => json_encode($data_before),
            'data_after' => json_encode($data_after),
            'history_details' => 'history_details'
        ));

        if ($result === false) {
            return $this->dataError(
                log: true,
                code: 500,
                data: null,
                message: $error,
            );
        }


        return $this->dataSuccess(
            log: true,
            code: 201,
            data: array("redirect_link" => "master_kompensasi/id/" . $compensation_id),
            // data: null,
            message: 'Successfully Updated System',
        );
    }

    /**
     * @var array $params
     * @return array
     * ----------------------------------------------------
     * name: getMasterSystem($system_type, $system_code)
     * desc: Service to loaded system details
     */
    public function getMasterCompensation(string $compenstation_id): array
    {
        try {
            $data = $this->repository->findByOtherKey(array(
                'compensation_id' => $compenstation_id
            ));
            $data->total_compensation = $this->decrypt($data->total_compensation);
            // data_dump($data);
        } catch (\Exception $e) {
            return $this->dataError(
                log: true,
                code: 500,
                data: null,
                message: $e->getMessage()
            );
        }

        return $this->dataSuccess(
            log: true,
            code: 200,
            data: $data,
            message: 'Successfully Loaded System Data',
        );
    }

    public function datatableDetail(?array $payload): array
    {
        $this->serviceAction = '[MASTER_KOMPENSASI][TABLE_DETAIL]';
        // Configure datatable settings
        // --------------------------------

        $formattedFields = function ($item) {
            return [
                $item->compensation_id,
                labelDate(isEmpty($item->created_dt)),
                lower(isEmpty($item->created_by)),
                isEmpty($item->compensation_description),
            ];
        };

        // Instance datatable class
        // --------------------------------
        $table = new Datatable($this->repository, $payload);
        return $this->dataSuccess(
            code: 200,
            data: $table->getRows(fn ($items) => array_map($formattedFields, $items)),
            message: 'Successfully loaded datatable system data.',
        );
    }


    /**
     * @var request $params
     * @return array
     * ----------------------------------------------------
     * name: removeSelected($params)
     * desc: Service to delete selected allowance data
     */
    public function removeSelected($body): array
    {
        $this->serviceAction = '[[MASTER_COMPENSATION][DELETE]';

        $repository = $this;
        $error      = null;

        $result     = queryTransaction(function () use ($body, $repository,) {
            $ids = explode(",", $body['ids']);

            if (!empty($ids)) {
                foreach ($ids as $value) {
                    $allowance = $repository->repository->delete(array('compensation_id' => $value));

                    $this->payrollLogService->create(array(
                        'function_id' => $this->functionId,
                        'refference_id' => (string) $value,
                        'data_before' => '',
                        'data_after' => '',
                        'history_details' => $this->S_EMPLOYEE_NAME . '--> has deleted new compensation data'
                    ));
                }
            }

            return $allowance;
        }, $error);

        if ($result === false) {
            return $this->dataError(
                log: true,
                code: 500,
                data: null,
                message: 'Failed to delete selected data' . ' --> ' . $error,
            );
        }



        return $this->dataSuccess(
            log: true,
            code: 204,
            data: array('redirect_link' => 'master_tunjangan'),
            message: 'Successfully Deleted Selected Compensation Data',
        );
    }

    public function actionDownload(array $payload)
    {
        try {
            $filters = array();
            if (isset($payload['company_id'])) {
                $filters['tb_r_payroll_compensation.company_id'] = $payload['company_id'];
            }
            if (isset($payload['work_unit_id'])) {
                $filters['tb_r_payroll_compensation.work_unit_id'] = $payload['work_unit_id'];
            }
            if (isset($payload['role_id'])) {
                $filters['tb_r_payroll_compensation.role_id'] = $payload['role_id'];
            }
            if (isset($payload['employee_id'])) {
                $filters['tb_r_payroll_compensation.employee_id'] = $payload['employee_id'];
            }
            if (isset($payload['compensationType'])) {
                $filters['tb_m_system.compensation_type'] = $payload['compensationType'];
            }
            if (isset($payload['valid_from'])) {
                $filters['valid_from'] = $payload['valid_from'];
            }
            if (isset($payload['valid_to'])) {
                $filters['valid_to'] = $payload['valid_to'];
            }

            $data = $this->repository->findAllFilteredRecords($filters, [], $this->downloadService->fields);

            // echo '<pre>';
            // print_r($data);
            // die();

            $this->downloadService->setFileName("MasterCompensation_" . date('YmdHis') . "_" . time() . ".xlsx");
            $this->downloadService->setWorksheetName("Master Data Compensation");
            $filePath = $this->downloadService->generate($data);
            return $this->dataSuccess(
                log: true,
                code: 200,
                data: $filePath,
                message: 'Successfully Downloaded Excel',
            );
        } catch (\Throwable $th) {
            data_dump($th->getMessage());
            return $this->dataSuccess(
                log: true,
                code: 500,
                data: null,
                message: $th->getMessage(),
            );
        }
    }
}
