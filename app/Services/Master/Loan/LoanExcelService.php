<?php

namespace App\Services\Master\Loan;

/**
 * @author lutfi.hakim@arkamaya.co.id
 * @since May 2024
 */

use App\Helpers\Datatable;
use App\Repositories\Master\Loan\Temporary\LoanRepository;
use App\Services\Master\Loan\Excel\LoanDownloadService;
use App\Services\Master\Loan\Excel\LoanUploadService;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;

use DateTime;

class LoanExcelService extends LoanService{
    protected mixed $tempLoanRepo;

    public function __construct()
    {
        parent::__construct();
        $this->tempLoanRepo = new LoanRepository();
    }

    
    /**
     * @var array $payload
     * @return array
     * ----------------------------------------------------
     * name : datatable()
     * desc : Service to loaded datatable
     */
    public function datatable_uploaded(?array $payload) : array
    {
        $this->serviceAction = '[MASTER_LOAN][INQUIRY_UPLOAD]';
        // Configure datatable settings
        // --------------------------------
        $filters = function() use ($payload) {
            $filters = array();
            if(isset($payload['process_id'])) $filters['process_id'] = $payload['process_id'];

            return $filters;
        };
        
        $formattedFields = function ($item) {
            return [
                $item->valid_flg,
                $item->update_flg,
                isEmpty($item->company_name),
                isEmpty($item->no_reg),
                isEmpty($item->employee_name),  
                isEmpty($item->loan_type_name),
                isEmpty($item->loan_duration_name),
                isEmpty($item->deduction_period_start),
                isEmpty($this->decrypt($item->loan_total)),
                isEmpty($item->loan_description),
                isEmpty($item->created_by),
                labelDate(isEmpty($item->created_dt)),
                isEmpty($item->changed_by),
                labelDate(isEmpty($item->changed_dt)),
                $item->error_message,
                $item->valid_flg."~".$item->update_flg,
            ];
        };
        
        // Instance datatable class
        // --------------------------------
        $table = new Datatable($this->tempLoanRepo, $payload);
        $table->setFilters($filters);

        return $this->dataSuccess( 
            code : 200,
            data : $table->getRows(fn($items) => array_map($formattedFields, $items)),
            message : 'Successfully loaded datatable allowance uploaded data.', 
        );
    }


    /**
     * @var request $params
     * @return array
     * ----------------------------------------------------
     * name: downloadExcelTemplate($payload)
     * desc: Service to download excel template
     */
    public function downloadExcelTemplate(array $payload)
    {
        $company_id = isset($payload['company_id']) ? $payload['company_id'] : '';
        try {
            $filters = array();
            $likeFilters = array();
            
            $data = $this->loanRepository->findAllFilteredRecords($filters, $likeFilters, $this->excelService->fields);

            $companies = $this->companyRepository->findAll(array('company_id' => $company_id));
            $loan_type = $this->systemRepository->findAll(array('system_type' => 'loan_type'));
            $loan_duration = $this->systemRepository->findAll(array('system_type' => 'loan_duration'));

            $templateExcelService = new LoanDownloadService($companies,$loan_type,$loan_duration);
            
            $templateExcelService->setFileName("TemplateUpload_MasterLoan_".date('YmdHis')."_".time().".xlsx");
            $filePath = $templateExcelService->generate($data);

            return $this->dataSuccess( 
                log : true,
                code : 200,
                data : $filePath, 
                message : 'Successfully Downloaded Excel', 
            );
        } catch (\Throwable $th) {
            return $this->dataSuccess( 
                log : true,
                code : 500,
                data : null, 
                message : $th->getMessage(), 
            );
        }
    }

    /**
     * @var request $params
     * @return array
     * ----------------------------------------------------
     * name: uploadExcel($payload, $file)
     * desc: Service to download excel template
     */
    public function uploadExcel(array $payload, $file)
    {
        try {
            $company_id = $payload['company_id'];
            $uploadExcelService = new LoanUploadService();
            $companyRepository = $this->companyRepository;
            $employeeRepository = $this->employeeRepository;
            $systemRepository = $this->systemRepository;
            $data = $uploadExcelService->readExcel($file);

            $results = array();
            $totalError = 0;
            $transactionId = 0;
            $errorTemplate = fn($val) => "<li>{$val}</li>";
            $processId = isset($payload['process_id']) ? $payload['process_id'] : $company_id.date('YmdHis').time();
            $loanData= array();
            $loanIncrement = 0;

            $whereProcessId = array('process_id' => $processId);
            $this->tempLoanRepo->delete($whereProcessId);

            if(!empty($data)){
                $results = array_map(function($item,) use(
                    $processId,
                    $company_id,
                    $companyRepository, 
                    $systemRepository,
                    $employeeRepository,
                    &$transactionId,
                    &$totalError, 
                    &$loanData,
                    &$loanIncrement,
                    $errorTemplate
                )
                {
                    $errorMessage = '';
                    $updateFlg = 0;
                    $errorFlg = 0;

                    /**Check Company By Name */
                    $isExistsCompany = $companyRepository->isExistsCompanyName($item[1]);
                    if(empty($isExistsCompany)){
                        $errorFlg++;
                        $errorMessage .= $errorTemplate("<strong>  ". lang("Shared.label.company") . " : {$item[1]}</strong> ". lang("Loan.upload.validation.notfound") ."");
                    } else {
                        if($isExistsCompany->company_id != $company_id){
                            $errorFlg++;
                            $errorMessage .= $errorTemplate("<strong> ". lang("Shared.label.company") . " : {$item[1]}</strong> template tidak sesuai dengan pilihan perusahaan");
                        }
                    }

                    /**Exists Loan Type */
                    $isExistLoanType = $systemRepository->findByOtherKey(array(
                        'LOWER(system_type)=' => 'loan_type',
                        'LOWER(system_value_txt)=' => strtolower($item[4])
                    ));
                    if(empty($isExistLoanType)){
                        $errorFlg++;
                        $errorMessage .= $errorTemplate("<strong> ". lang("Loan.inquiry.loan_type") . " : {$item[4]}</strong> ". lang("Loan.upload.validation.notfound"));
                    }

                    /**Exists Loan Type */
                    $isExistLoanDuration = $systemRepository->findByOtherKey(array(
                        'LOWER(system_type)=' => 'loan_duration',
                        'LOWER(system_value_txt)=' => strtolower($item[5])
                    ));
                    if(empty($isExistLoanDuration)){
                        $errorFlg++;
                        $errorMessage .= $errorTemplate("<strong> ". lang("Loan.inquiry.loan_term") . " : {$item[5]}</strong> ". lang("Loan.upload.validation.notfound"));
                    }

                    $employee_id = '-';
                    $work_unit_id = '-';
                    $employeeData = $employeeRepository->findByOtherKey(
                        array(
                            'no_reg' => $item[2], 
                            'employee_name' => $item[3]
                        )
                    );

                    if($employeeData->employee_id == ''){
                        $errorFlg++;
                        $errorMessage .= $errorTemplate("<strong> employee with no reg : $item[2] and name : $item[3] </strong> ".lang("Loan.upload.validation.notfound"));
                    } else {
                        $employee_id = $employeeData->employee_id;
                        $work_unit_id = $employeeData->work_unit_id;
                    }

                    $deduction_period_start = $item[6];
                    if (!$this->isValidDate($item[6])) {
                        $errorFlg++;
                        $errorMessage .= $errorTemplate("<strong> Invalid format date  {$item[6]}  </strong> Tanggal awal potong");
                        $deduction_period_start = '1997-01-01';
                    }

                    $loan_total = $item[7];
                    if (!$this->isNumeric($item[7])) {
                        $errorFlg++;
                        $errorMessage .= $errorTemplate("<strong> Invalid format number  {$item[7]} </strong> Total pinjaman ");
                        $loan_total = '0';
                    }
                    
                    $transactionId++;
                    $totalError = $errorFlg;

                    $isValid = 1;
                    if($errorFlg > 0) {
                        $isValid = 0;
                        $errorMessage = "<ol>{$errorMessage}</ol>";
                    }

                    $loan_duration = 0;
                    if($isExistLoanDuration){
                        $loan_duration = $isExistLoanDuration->system_code;
                        $monthly_deduction =  $this->encrypt(round((int) $loan_total / (int) $loan_duration, 0));
                    } else {
                        $monthly_deduction = $this->encrypt(decimalvalue(0));
                    }
                    
                    $deduction_period_end = (new DateTime($deduction_period_start))->modify('+' . $loan_duration . ' months')->format('Y-m-d');

                    return array(
                        'process_id' => $processId,
                        'transaction_id' => $transactionId,
                        'company_id' => (!empty($isExistsCompany) ? $isExistsCompany->company_id : ''),
                        'employee_id' => $employee_id,
                        'work_unit_id' => $work_unit_id,
                        'loan_type' => !empty($isExistLoanType) ? $isExistLoanType->system_code : '-',
                        'loan_duration' => !empty($isExistLoanDuration) ? $isExistLoanDuration->system_code : '-',
                        'deduction_period_start' => $deduction_period_start,
                        'deduction_period_end' => $deduction_period_end,
                        'loan_total' => $this->encrypt(decimalvalue($loan_total)),                        
                        'monthly_deduction' => $monthly_deduction,
                        'remaining_loan' => $this->encrypt(decimalvalue($loan_total)),
                        'loan_description' => $item[8],
                        'loan_paid_off' => '0',
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

            $this->tempLoanRepo->delete(array('created_by' => $this->S_NO_REG));
            $this->tempLoanRepo->insertBatch($results);

            return $this->dataSuccess( 
                log : true,
                code : 200,
                data : array(
                    'total_error' => $totalError,
                    'is_valid' => ($totalError > 0) ? 'false' : 'true',
                    'process_id' => $processId
                ), 
                message : 'Successfully Uploaded Excel', 
            );
        } catch (\Exception $th) {
            return $this->dataError( 
                log : true,
                code : 500,
                data : null, 
                message : $th->getMessage(), 
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
        $totalInvalid = $this->tempLoanRepo->countAllResults(array('process_id' => $payload['process_id'], 'valid_flg' => '0'));
        return $this->dataSuccess( 
            log : true,
            code : 200,
            data : array(
                'totalInvalid' => $totalInvalid
            ),
            message : 'Successfully Uploaded Excel', 
        );
    }
    
    /**
     * @var request $params
     * @return array
     * ----------------------------------------------------
     * name: getInvalidData($payload)
     * desc: Service to download excel template
     */
    public function submitExcelTemplate(array $payload)
    {
        $companyId = isset($payload['company_id']) ? (!empty($payload['company_id']) ? $payload['company_id'] : '') : '';
        $processId = isset($payload['process_id']) ? (!empty($payload['process_id']) ? $payload['process_id'] : '') : '';

        $this->serviceAction = '[MASTER_LOAN][UPLOADEXCEL]';

        $repository = $this;
        $error = null;
        
        $result = queryTransaction(function() use ($processId,$repository,$companyId) 
        {
            $saveHeader = $repository->tempLoanRepo->importExcel($processId, $companyId);

            return $saveHeader;
        }, 
        $error);

        if ($result === false) {
            return $this->dataError( 
                log : true,
                code : 500,
                data : null,
                message : 'Failed Upload Master Loan --> ' . $error,
            );
        }
        
        return $this->dataSuccess( 
            log : true,
            code : 201,
            data : array ('redirect_link'=>'master_loan/upload'),
            message : 'Successfully Upload Master Loan', 
        );
        
    }

    function isValidDate($dateString) {
        $date = DateTime::createFromFormat('Y-m-d', $dateString);
        if ($date && $date->format('Y-m-d') === $dateString) {
            return true; // The date is valid
        }
        return false; // The date is invalid
    }

    function isNumeric($str) {
        return ctype_digit((string) $str);
    }

}