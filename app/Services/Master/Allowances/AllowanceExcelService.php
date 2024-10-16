<?php

namespace App\Services\Master\Allowances;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Helpers\Datatable;
use App\Repositories\Master\Allowances\Temporary\AllowanceAreaRepository;
use App\Repositories\Master\Allowances\Temporary\AllowanceRepository;
use App\Repositories\Master\Allowances\Temporary\AllowanceRulesRepository;
use App\Services\Master\Allowances\Excel\AllowanceDownloadService;
use App\Services\Master\Allowances\Excel\AllowanceUploadService;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AllowanceExcelService extends AllowanceService{
    protected mixed $tempAllowanceRepo;
    protected mixed $tempAllowanceAreaRepo;
    protected mixed $tempAllowanceRulesRepo;

    public function __construct()
    {
        parent::__construct();

        $this->tempAllowanceRepo = new AllowanceRepository();
        $this->tempAllowanceAreaRepo = new AllowanceAreaRepository();
        $this->tempAllowanceRulesRepo = new AllowanceRulesRepository();
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
        $this->serviceAction = '[MASTER_ALLOWANCE][INQUIRY_UPLOAD]';
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
                isEmpty($item->allowance_code),
                isEmpty($item->allowance_name),
                number($this->decrypt($item->default_value)),
                isEmpty($item->minimum_working_period),
                isEmpty($item->calculation_type_name),
                isEmpty($item->calculation_mode_name),
                isEmpty($item->gl_name),
                std_date($item->effective_date, 'Y-m-d', 'd F Y'),
                std_date($item->effective_date_end, 'Y-m-d', 'd F Y'),
                isEmpty($item->list_area),
                isEmpty($item->list_group),
                isEmpty($item->list_rules),
                isEmpty($item->created_by),
                std_date($item->created_dt, 'Y-m-d H:i:s', 'd F Y'),
                isEmpty($item->changed_by),
                std_date($item->changed_dt, 'Y-m-d H:i:s', 'd F Y'),
                $item->error_message,
                $item->valid_flg."~".$item->update_flg,
            ];
        };
        
        // Instance datatable class
        // --------------------------------
        $table = new Datatable($this->tempAllowanceRepo, $payload);
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
            
            $data = $this->allowanceRepository->findAllFilteredRecords($filters, $likeFilters, $this->excelService->fields);

            $companies = $this->companyRepository->findAll(array('company_id' => $company_id));
            $area = $this->areaRepository->findAll(array('company_id' => $company_id));
            $grup = $this->systemRepository->findAll(array('system_type' => 'area_group'));
            $rules = $this->areaRulesRepo->findAll(array('company_id' => $company_id, 'rules_type' => '0'));

            $templateExcelService = new AllowanceDownloadService($companies,$area,$grup,$rules);
            
            $templateExcelService->setFileName("TemplateUpload_MasterAlowance_".date('YmdHis')."_".time().".xlsx");
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
            $uploadExcelService = new AllowanceUploadService();
            $companyRepository = $this->companyRepository;
            $data = $uploadExcelService->readExcel($file);

            $results = array();
            $totalError = 0;
            $transactionId = 0;
            $errorTemplate = fn($val) => "<li>{$val}</li>";
            $processId = isset($payload['process_id']) ? $payload['process_id'] : $company_id.date('YmdHis').time();
            $allowanceArea = array();
            $allowanceGrup = array();
            $allowancePayrolRules = array();

            $areaIncrement = 0;
            $grupIncrement = 0;
            $rulesIncrement = 0;

            $whereProcessId = array('process_id' => $processId);
            $this->tempAllowanceRepo->delete($whereProcessId);
            $this->tempAllowanceAreaRepo->delete($whereProcessId);
            $this->tempAllowanceAreaRepo->delete($whereProcessId);
            $this->tempAllowanceRulesRepo->delete($whereProcessId);

            if(!empty($data)){
                $results = array_map(function($item,) use(
                    $processId,
                    $company_id,
                    $companyRepository, 
                    &$transactionId,
                    &$totalError, 
                    &$allowanceArea,
                    &$allowanceGrup,
                    &$allowancePayrolRules,
                    &$areaIncrement,
                    &$grupIncrement,
                    &$rulesIncrement,
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
                        $errorMessage .= $errorTemplate("<strong>{$item[1]}</strong> tidak ada dalam database");
                    } else {
                        if($isExistsCompany->company_id != $company_id){
                            $errorFlg++;
                            $errorMessage .= $errorTemplate("<strong>{$item[1]}</strong> template tidak sesuai dengan pilihan perusahaan");
                        }
                    }
                    
                    /**Exists Allowance */
                    $isExistsAllowance = $this->allowanceRepository->findByOtherKey(array(
                        'company_id' => $company_id,
                        'allowance_code' => $item[2]
                    ));
                    if(!empty($isExistsAllowance)){
                        $updateFlg++;
                    }

                    /**Exists Allowance Calculation Type */
                    $isExistsCalculationType = $this->systemRepository->findByOtherKey(array(
                        'LOWER(system_type)=' => 'calculation_type',
                        'LOWER(system_value_txt)=' => strtolower($item[6])
                    ));
                    if(empty($isExistsCalculationType)){
                        $errorFlg++;
                        $errorMessage .= $errorTemplate("<strong>{$item[6]}</strong> ".lang("Allowances.upload.validation.notfound"));
                    }

                    /**Exists Allowance Calculation Mode */
                    $isExistsCalculationMode = $this->systemRepository->findByOtherKey(array(
                        'LOWER(system_type)=' => 'calculation_mode',
                        'LOWER(system_value_txt)=' => strtolower($item[7])
                    ));
                    if(empty($isExistsCalculationMode)){
                        $errorFlg++;
                        $errorMessage .= $errorTemplate("<strong>{$item[7]}</strong> ".lang("Allowances.upload.validation.notfound"));
                    }
                    
                    /**Exists Allowance Calculation Mode */
                    $isExistGlAccount = $this->glAccountRepo->findByOtherKey(array(
                        'company_id' => $company_id,
                        'LOWER(gl_code)=' => $item[8],
                    ));
                    if(empty($isExistGlAccount)){
                        $errorFlg++;
                        $errorMessage .= $errorTemplate("<strong>{$item[8]}</strong> ".lang("Allowances.upload.validation.notfound"));
                    }

                    $transactionId++;

                    /**Check Area */
                    $area = explode(",", $item[10]);
                    
                    if(!empty($area)){
                        $i = 1;
                        $j = 0;
                        $allowanceAreaLabel = array();
                        $areaError = array();
                        foreach ($area as $value) {
                            $error = false;
                            $checkIfexistsArea = $this->areaRepository->findByOtherKey(array(
                                'company_id' => $company_id, 
                                'name' => $value, 
                            ));

                            if(!empty($checkIfexistsArea)){
                                $allowanceArea[$areaIncrement]['process_id'] = $processId;
                                $allowanceArea[$areaIncrement]['transaction_id'] = $transactionId;
                                $allowanceArea[$areaIncrement]['seq_no'] = $i;
                                $allowanceArea[$areaIncrement]['area_type'] = '0';
                                $allowanceArea[$areaIncrement]['area_id'] = !empty($checkIfexistsArea) ? $checkIfexistsArea->work_unit_id : '';
                                $allowanceArea[$areaIncrement]['created_by'] = $this->S_NO_REG;
                                $allowanceArea[$areaIncrement]['created_dt'] = date('Y-m-d H:i:s');
                                $allowanceArea[$areaIncrement]['changed_by'] = $this->S_NO_REG;
                                $allowanceArea[$areaIncrement]['changed_dt'] = date('Y-m-d H:i:s');
                                $areaIncrement++;
                                $i++;

                                $allowanceAreaLabel[$j] = !empty($checkIfexistsArea) ? $checkIfexistsArea->name : '';
                            } else {
                                $errorFlg++;
                                $error = true;
                                $areaError[] = $value; 
                                $allowanceAreaLabel[$j] = $value;
                            }

                            $j++;
                        }

                        if(!empty($areaError)){
                            $errorMessage .= $errorTemplate("Area: (<strong>".implode(',',$areaError)."</strong>) ".lang("Allowances.upload.validation.notfound"));
                        }
                    }

                    /**Check Grup */
                    $grup = explode(",", $item[11]);
                    
                    if(!empty($grup)){
                        $j = 0;
                        $allowanceGrupLabel = array();
                        $areaGrupError = array();
                        foreach ($grup as $value) {
                            $error = false;
                            $checkIfexistsGrup = $this->systemRepository->findByOtherKey(array(
                                'system_type' => 'area_group', 
                                'LOWER(system_value_txt)=' => $value, 
                            ));

                            if(!empty($checkIfexistsGrup)){
                                $allowanceGrup[$grupIncrement]['process_id'] = $processId;
                                $allowanceGrup[$grupIncrement]['transaction_id'] = $transactionId;
                                $allowanceGrup[$grupIncrement]['seq_no'] = $i;
                                $allowanceGrup[$grupIncrement]['area_type'] = '1';
                                $allowanceGrup[$grupIncrement]['area_id'] = !empty($checkIfexistsGrup) ? $checkIfexistsGrup->system_code : '';
                                $allowanceGrup[$grupIncrement]['created_by'] = $this->S_NO_REG;
                                $allowanceGrup[$grupIncrement]['created_dt'] = date('Y-m-d H:i:s');
                                $allowanceGrup[$grupIncrement]['changed_by'] = $this->S_NO_REG;
                                $allowanceGrup[$grupIncrement]['changed_dt'] = date('Y-m-d H:i:s');
                                $grupIncrement++;
                                $i++;
                                
                                $allowanceGrupLabel[$j] = !empty($checkIfexistsGrup) ? $checkIfexistsGrup->system_value_txt : '';
                            } else {
                                $errorFlg++;
                                $areaGrupError[] = $value;
                                $error = true;
                                
                                $allowanceGrupLabel[$j] = $value;
                            }
                            
                            $j++;
                        }
                        
                        if(!empty($areaGrupError)){
                            $errorMessage .= $errorTemplate("Area Grup: (<strong>".implode(',',$areaGrupError)."</strong>) ".lang("Allowances.upload.validation.notfound"));
                        }
                    }
                    
                    /**Check Rules */
                    $rules = explode(",", $item[12]);
                    
                    if(!empty($rules)){
                        $i = 1;
                        $j = 0;
                        $allowanceRulesLabel = array();
                        $areaRulesError = array();
                        foreach ($rules as $value) {
                            $error = false;
                            $checkIfexistsRules = $this->areaRulesRepo->findByOtherKey(array(
                                'company_id' => $company_id, 
                                'LOWER(rules_code)=' => $value, 
                                'rules_type' => '0'
                            ));

                            if(!empty($checkIfexistsRules)){
                                $allowancePayrolRules[$rulesIncrement]['process_id'] = $processId;
                                $allowancePayrolRules[$rulesIncrement]['transaction_id'] = $transactionId;
                                $allowancePayrolRules[$rulesIncrement]['seq_no'] = $i;
                                $allowancePayrolRules[$rulesIncrement]['rules_id'] = !empty($checkIfexistsRules) ? $checkIfexistsRules->payroll_rules_id : '';
                                $allowancePayrolRules[$rulesIncrement]['created_by'] = $this->S_NO_REG;
                                $allowancePayrolRules[$rulesIncrement]['created_dt'] = date('Y-m-d H:i:s');
                                $allowancePayrolRules[$rulesIncrement]['changed_by'] = $this->S_NO_REG;
                                $allowancePayrolRules[$rulesIncrement]['changed_dt'] = date('Y-m-d H:i:s');
                                $rulesIncrement++;
                                $i++;
                                
                                $allowanceRulesLabel[$j] = !empty($checkIfexistsRules) ? $checkIfexistsRules->rules_code : '';
                            } else {
                                $errorFlg++;
                                $areaRulesError[] = $value;
                                $error = true;
                                
                                $allowanceRulesLabel[$j] = $value;
                            }
                            
                            $j++;
                        }
                        
                        if(!empty($areaRulesError)){
                            $errorMessage .= $errorTemplate("Payroll Rules: (<strong>".implode(',',$areaRulesError)."</strong>) ".lang("Allowances.upload.validation.notfound"));
                        }
                    }

                    $totalError = $errorFlg;

                    $isValid = 1;
                    if($errorFlg > 0) {
                        $isValid = 0;
                        $errorMessage = "<ol>{$errorMessage}</ol>";
                    }
                    
                    return array(
                        'process_id' => $processId,
                        'transaction_id' => $transactionId,
                        'company_id' => (!empty($isExistsCompany) ? $isExistsCompany->company_id : ''),
                        'company_name' => (!empty($isExistsCompany) ? $isExistsCompany->company_name : ''),
                        'allowance_code' => $item[2],
                        'allowance_name' => $item[3],
                        'default_value' => $this->encrypt($item[4]),
                        'minimum_working_period' => $item[5],
                        'calculation_type' => !empty($isExistsCalculationType) ? $isExistsCalculationType->system_code : '-',
                        'calculation_mode' => !empty($isExistsCalculationMode) ? $isExistsCalculationMode->system_code : '-',
                        'gl_id' => (!empty($isExistGlAccount) ? $isExistGlAccount->gl_id : ''),
                        'effective_date' => std_date($item[9],'Y-m-d','Y-m-d'),
                        'effective_date_end' => '2999-12-31',
                        'list_area' => !empty($allowanceAreaLabel) ? implode(",",$allowanceAreaLabel) : '',
                        'list_group' => !empty($allowanceGrupLabel) ? implode(",",$allowanceGrupLabel) : '',
                        'list_rules' => !empty($allowanceRulesLabel) ? implode(",",$allowanceRulesLabel) : '',
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
            
            $this->tempAllowanceRepo->delete(array('created_by' => $this->S_NO_REG));
            $this->tempAllowanceRepo->insertBatch($results);

            $this->tempAllowanceAreaRepo->delete(array('created_by' => $this->S_NO_REG, 'area_type' => '0'));
            $this->tempAllowanceAreaRepo->insertBatch($allowanceArea);
            
            $this->tempAllowanceAreaRepo->delete(array('created_by' => $this->S_NO_REG, 'area_type' => '1'));
            $this->tempAllowanceAreaRepo->insertBatch($allowanceGrup);
            
            $this->tempAllowanceRulesRepo->delete(array('created_by' => $this->S_NO_REG));
            $this->tempAllowanceRulesRepo->insertBatch($allowancePayrolRules);

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
        $totalInvalid = $this->tempAllowanceRepo->countAllResults(array('process_id' => $payload['process_id'], 'valid_flg' => '0'));
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

        $this->serviceAction = '[MASTER_ALLOWANCE][UPLOADEXCEL]';

        $repository = $this;
        $error = null;
        
        $result = queryTransaction(function() use ($processId,$repository,$companyId) 
        {
            $saveHeader = $repository->tempAllowanceRepo->importExcel($processId, $companyId);

            if($saveHeader){
                $repository->tempAllowanceAreaRepo->importExcelArea($processId);
                $repository->tempAllowanceAreaRepo->importExcelGrup($processId);
                $repository->tempAllowanceRulesRepo->importExcelRules($processId);

                $repository->tempAllowanceRepo->delete(array('process_id' => $processId));
                $repository->tempAllowanceAreaRepo->delete(array('process_id' => $processId));
                $repository->tempAllowanceRulesRepo->delete(array('process_id' => $processId));
            }

            return $saveHeader;
        }, 
        $error);

        if ($result === false) {
            return $this->dataError( 
                log : true,
                code : 500,
                data : null,
                message : 'Failed Upload Master Allowances --> ' . $error,
            );
        }
        
        return $this->dataSuccess( 
            log : true,
            code : 201,
            data : array ('redirect_link'=>'master_tunjangan/upload'),
            message : 'Successfully Upload Master Allowances', 
        );
        
    }
}