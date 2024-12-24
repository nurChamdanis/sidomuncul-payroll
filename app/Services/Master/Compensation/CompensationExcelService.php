<?php

namespace App\Services\Master\Compensation;

use App\Repositories\Master\CompensationTemporary\TemporaryRepository;
use App\Repositories\Master\MasterCompensationRepository;
use App\Services\Master\MasterCompensationService;
use App\Services\Master\Compensation\CompensationDownloadService;
use App\Services\Master\Compensation\CompensationTemplateService;
use App\Helpers\Datatable;
use PhpParser\Node\Stmt\TryCatch;

class CompensationExcelService extends MasterCompensationService
{
    protected $tempRepository;
    protected $masterRepository;
    protected $downloadService;

    public function __construct()
    {
        parent::__construct();
        $this->tempRepository = new TemporaryRepository();
        $this->masterRepository = new MasterCompensationRepository();
        $this->downloadService = new CompensationDownloadService();
    }

    public function uploadDataTable(?array $payload)
    {
        $this->serviceAction = '[MASTER_COMPENSATION][INQUIRY_UPLOAD]';

        $filters = function () use ($payload) {
            $filters = array();
            if (isset($payload['process_id'])) $filters['process_id'] = $payload['process_id'];

            return $filters;
        };

        function labelDateCustom($val)
        {
            if (!empty($val) && $val != '-') {
                // Adjust the delimiter from '/' to '-'
                list($year, $month) = explode('-', $val) + [null, null];

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
            return [
                $item->valid_flg,
                $item->update_flg,
                isEmpty($item->company_name),
                isEmpty($item->name),
                isEmpty($item->role_name),
                isEmpty($item->no_reg),
                isEmpty($item->employee_name),
                labelDateCustom(isEmpty($item->period)),
                // isEmpty($item->period),
                isEmpty($item->system_value_txt),
                number($this->decrypt($item->total_compensation)),
                $item->error_message,
                $item->valid_flg . "~" . $item->update_flg,
            ];
        };

        // Instance datatable class
        // --------------------------------
        $table = new Datatable($this->tempRepository, $payload);
        $table->setFilters($filters);

        return $this->dataSuccess(
            code: 200,
            data: $table->getRows(fn ($items) => array_map($formattedFields, $items)),
            message: 'Successfully loaded datatable compensation upload data.',
        );
    }



    public function actionUpload(array $payload, $file)
    {

        try {
            $company_id = $payload['company_id'];
            $uploadExcelService = $this->uploadService;
            $companyRepository = $this->companyRepository;
            $data = $uploadExcelService->readExcel($file);


            $results = array();
            $totalError = 0;
            $transactionId = 0;
            $errorTemplate = fn ($val) => "<li>{$val}</li>";
            $processId = isset($payload['process_id']) && $payload['process_id'] != 'undefined' ? $payload['process_id'] : $company_id . date('YmdHis') . time();

            $whereProcessId = array('process_id' => $processId);
            $this->tempRepository->delete($whereProcessId);

            // data_dump($data);
            // die;

            if (!empty($data)) {

                // write a function that loop $data and check if the company name is exist in the database

                $results = array_map(function ($item) use (
                    $company_id,
                    $processId,
                    $errorTemplate,
                    &$transactionId,
                ) {

                    $errorMessage = '';
                    $updateFlg = 0;
                    $errorFlg = 0;

                    // data_dump($item);

                    //check apa nama companynya exist di database dan cocok dengan company_id yang dipilih
                    $companyValidation = $this->tempRepository->getIdFromName("tb_m_company", "company_name", "company_id", $item[1]);
                    // data_dump($companyValidation);
                    if (empty($companyValidation)) {
                        $errorFlg++;
                        $errorMessage .= $errorTemplate("<strong>{$item[1]}</strong> ".lang("Compensation.upload.validation.not_found"));
                    } else if (empty($companyValidation) && $companyValidation != $company_id) {
                        $errorFlg++;
                        $errorMessage .= $errorTemplate("<strong>{$item[1]}</strong> ".lang("Compensation.upload.validation.not_match_c"));
                    }

                    // mengecek nomor karyawan dan namanya
                    $employeeNumberValidation = $this->tempRepository->getIdFromName("tb_m_employee", "no_reg", "employee_id", $item[2]);
                    if (empty($employeeNumberValidation)) {
                        $errorFlg++;
                        $errorMessage .= $errorTemplate("<strong>{$item[2]}</strong> ".lang("Compensation.upload.validation.not_found"));
                    }

                    // mengecek nama karyawannya
                    $employeeName = $this->tempRepository->getIdFromName("tb_m_employee", "employee_name", "employee_id", $item[3]);
                    if (empty($employeeName)) {
                        $errorFlg++;
                        $errorMessage .= $errorTemplate("<strong>{$item[3]}</strong> ".lang("Compensation.upload.validation.not_found"));
                    }

                    $employeeCheck = true;
                    //check if $employeeNumberValidation and $employeeName is match
                    if ($employeeNumberValidation != $employeeName) {
                        $errorFlg++;
                        $errorMessage .= $errorTemplate("<strong>{$item[2]}</strong> dan <strong>{$item[3]}</strong> ".lang("Compensation.upload.validation.not_match_e"));
                        $employeeCheck = false;
                    } else {
                        //mengecek work unit dari employee
                        $workUnitId = $this->tempRepository->getIdFromName("tb_m_employee", "employee_id", "work_unit_id",  $employeeName);
                    }


                    // mengecek jenis kompensasi
                    $compensationType = $this->tempRepository->getIdFromName("tb_m_system_payroll", "system_value_txt", "system_code", $item[4]);
                    if (empty($compensationType)) {
                        $errorFlg++;
                        $errorMessage .= $errorTemplate("<strong>{$item[4]}</strong> ".lang("Compensation.upload.validation.not_found"));
                    }

                    // mengecek jika datanya sudah ada di table maka aksi update dilakukan
                    $checkUpdate = $this->masterRepository->compensationCheck($employeeNumberValidation, $compensationType, $item[5]);
                    if (!empty($checkUpdate)) {
                        $updateFlg++;
                    }

                    // checking if period(item[5]) format is yyyy-mm e.g. 2021-01
                    $period = explode('-', $item[5]);
                    if (count($period) != 2) {
                        $errorFlg++;
                        $errorMessage .= $errorTemplate("<strong>{$item[5]}</strong> ".lang("Compensation.upload.validation.wrong_format"));
                    } else if (!is_numeric($period[0]) || !is_numeric($period[1]) || strlen($period[0]) != 4 || strlen($period[1]) != 2) {
                        $errorFlg++;
                        $errorMessage .= $errorTemplate("<strong>{$item[5]}</strong> ".lang("Compensation.upload.validation.wrong_format"));
                    }

                    // $totalError = $errorFlg;

                    $transactionId++;

                    $isValid = 1;
                    if ($errorFlg > 0) {
                        $isValid = 0;
                        $errorMessage = "<ol>{$errorMessage}</ol>";
                    }

                    return array(
                        'process_id' => $processId,
                        'transaction_id' => $transactionId,
                        'company_id' => $company_id,
                        // 'company_name' => $item[1],
                        'work_unit_id' => (!empty($workUnitId) ? $workUnitId : 0),
                        'employee_id' => (!empty($employeeCheck) ? $employeeNumberValidation : 0),
                        'compensation_type' => (!empty($compensationType) ? $compensationType : 0),
                        'period' => $item[5],
                        'total_compensation' => $this->encrypt(decimalvalue($item[6])),
                        'compensation_description' => $item[7],
                        'update_flg' => $updateFlg,
                        'valid_flg' => $isValid,
                        'error_message' => $errorMessage,
                        'created_by' => $this->S_NO_REG,
                        'created_dt' => date('Y-m-d H:i:s'),
                        'changed_by' => $this->S_NO_REG,
                        'changed_dt' => date('Y-m-d H:i:s'),
                    );
                }, $data);
            }

            // data_dump($results);

            $this->tempRepository->delete(array('created_by' => $this->S_NO_REG));
            $this->tempRepository->insertBatch($results);

            return $this->dataSuccess(
                log: true,
                code: 200,
                data: array(
                    'total_error' => $totalError,
                    'is_valid' => ($totalError > 0) ? 'false' : 'true',
                    'process_id' => $processId
                ),
                message: 'Successfully Uploaded Excel',
            );
        } catch (\Exception $th) {
            return $this->dataError(
                log: true,
                code: 500,
                data: null,
                message: $th->getMessage(),
            );
        }
    }

    /**
     * @var request $params
     * @return array
     * ----------------------------------------------------
     * name: getInvalidData($payload)
     * desc: Service to download excel template
     */
    public function getInvalidData(array $payload)
    {
        // data_dump($payload);
        $totalInvalid = $this->tempRepository->countAllResults(array('process_id' => $payload['process_id'], 'valid_flg' => '0'));
        return $this->dataSuccess(
            log: true,
            code: 200,
            data: array(
                'totalInvalid' => $totalInvalid
            ),
            message: 'Successfully Uploaded Excel',
        );
    }

    public function actionSubmitExcel(array $payload)
    {
        $companyId = isset($payload['company_id']) ? (!empty($payload['company_id']) ? $payload['company_id'] : '') : '';
        $processId = isset($payload['process_id']) ? (!empty($payload['process_id']) ? $payload['process_id'] : '') : '';

        $this->serviceAction = '[MASTER_COMPENSATION][UPLOADEXCEL]';

        $repository = $this;
        $error = null;
        
        $result = queryTransaction(function() use ($processId,$repository,$companyId) 
        {
            $saveExcel = $repository->tempRepository->importExcel($processId, $companyId);

            return $saveExcel;
        }, 
        $error);

        if ($result === false) {
            return $this->dataError( 
                log : true,
                code : 500,
                data : null,
                message : 'Failed Upload Master Compensation --> ' . $error,
            );
        }
        
        return $this->dataSuccess( 
            log : true,
            code : 201,
            data : array ('redirect_link'=>'master_kompensasi/upload'),
            message : 'Successfully Upload Master Compensation', 
        );
        
    }

    public function actionDownloadTemplate(array $payload){
        $company_id = isset($payload['company_id']) ? $payload['company_id'] : '';
        try {
            $filters = array();
            $likeFilters = array();
            
            $data = $this->masterRepository->findAllFilteredRecords($filters, $likeFilters, $this->downloadService->fields);

            $companies = $this->companyRepository->findAll(array('company_id' => $company_id));
            $compensationType = $this->systemRepository->findAll(array('system_type' => 'compensation_type'));


            $templateExcelService = new CompensationTemplateService($companies,$compensationType);
            
            $templateExcelService->setFileName("TemplateUpload_MasterCompensation_".date('YmdHis')."_".time().".xlsx");
            $filePath = $templateExcelService->generate($data);

            return $this->dataSuccess( 
                log : true,
                code : 200,
                data : $filePath, 
                message : 'Successfully Downloaded Excel', 
            );
        } catch (\Throwable $th) {
            data_dump($th->getMessage());
            return $this->dataSuccess( 
                log : true,
                code : 500,
                data : null, 
                message : $th->getMessage(), 
            );
        }
    }
}
