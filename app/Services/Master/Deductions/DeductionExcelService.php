<?php

namespace App\Services\Master\Deductions;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Helpers\Datatable;
use App\Repositories\Master\Deductions\Temporary\DeductionAreaRepository;
use App\Repositories\Master\Deductions\Temporary\DeductionRepository;
use App\Repositories\Master\Deductions\Temporary\DeductionRulesRepository;
use App\Services\Master\Deductions\Excel\DeductionDownloadService;
use App\Services\Master\Deductions\Excel\DeductionUploadService;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DeductionExcelService extends DeductionService{
    protected mixed $tempDeductionRepo;
    protected mixed $tempDeductionAreaRepo;
    protected mixed $tempDeductionRulesRepo;

    public function __construct()
    {
        parent::__construct();

        $this->tempDeductionRepo = new DeductionRepository();
        $this->tempDeductionAreaRepo = new DeductionAreaRepository();
        $this->tempDeductionRulesRepo = new DeductionRulesRepository();
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
        $this->serviceAction = '[MASTER_Deduction][INQUIRY_UPLOAD]';
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
                isEmpty($item->deduction_code),
                isEmpty($item->deduction_name),
                number($this->decrypt($item->default_value)),
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
        $table = new Datatable($this->tempDeductionRepo, $payload);
        $table->setFilters($filters);

        return $this->dataSuccess( 
            code : 200,
            data : $table->getRows(fn($items) => array_map($formattedFields, $items)),
            message : 'Successfully loaded datatable Deduction uploaded data.', 
        );
    }


    /**p
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
            
            $data = $this->deductionRepository->findAllFilteredRecords($filters, $likeFilters, $this->excelService->fields);

            $companies = $this->companyRepository->findAll(array('company_id' => $company_id));
            $area = $this->areaRepository->findAll(array('company_id' => $company_id));
            $grup = $this->systemRepository->findAll(array('system_type' => 'area_group'));
            $rules = $this->areaRulesRepo->findAll(array('company_id' => $company_id, 'rules_type' => '1'));

            $templateExcelService = new DeductionDownloadService($companies,$area,$grup,$rules);
            
            $templateExcelService->setFileName("TemplateUpload_MasterDeductions_".date('YmdHis')."_".time().".xlsx");
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
            $uploadExcelService = new DeductionUploadService();
            $companyRepository = $this->companyRepository;
            $data = $uploadExcelService->readExcel($file);

            $results = array();
            $totalError = 0;
            $transactionId = 0;
            $errorTemplate = fn($val) => "<li>{$val}</li>";
            $processId = isset($payload['process_id']) ? $payload['process_id'] : $company_id.date('YmdHis').time();
            $DeductionArea = array();
            $DeductionGrup = array();
            $DeductionPayrolRules = array();

            $areaIncrement = 0;
            $grupIncrement = 0;
            $rulesIncrement = 0;

            $whereProcessId = array('process_id' => $processId);
            $this->tempDeductionRepo->delete($whereProcessId);
            $this->tempDeductionAreaRepo->delete($whereProcessId);
            $this->tempDeductionAreaRepo->delete($whereProcessId);
            $this->tempDeductionRulesRepo->delete($whereProcessId);

            if(!empty($data)){
                $results = array_map(function($item,) use(
                    $processId,
                    $company_id,
                    $companyRepository, 
                    &$transactionId,
                    &$totalError, 
                    &$DeductionArea,
                    &$DeductionGrup,
                    &$DeductionPayrolRules,
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
                    
                    /**Exists Deduction */
                    $isExistsDeduction = $this->deductionRepository->findByOtherKey(array(
                        'company_id' => $company_id,
                        'deduction_code' => $item[2]
                    ));
                    if(!empty($isExistsDeduction)){
                        $updateFlg++;
                    }

                    /**Exists Deduction Calculation Type */
                    $isExistsCalculationType = $this->systemRepository->findByOtherKey(array(
                        'LOWER(system_type)=' => 'calculation_type',
                        'LOWER(system_value_txt)=' => strtolower($item[5])
                    ));
                    if(empty($isExistsCalculationType)){
                        $errorFlg++;
                        $errorMessage .= $errorTemplate("<strong>{$item[5]}</strong> ".lang("Deductions.upload.validation.notfound"));
                    }

                    /**Exists Deduction Calculation Mode */
                    $isExistsCalculationMode = $this->systemRepository->findByOtherKey(array(
                        'LOWER(system_type)=' => 'calculation_mode',
                        'LOWER(system_value_txt)=' => strtolower($item[6])
                    ));
                    if(empty($isExistsCalculationMode)){
                        $errorFlg++;
                        $errorMessage .= $errorTemplate("<strong>{$item[6]}</strong> ".lang("Deductions.upload.validation.notfound"));
                    }
                    
                    /**Exists Deduction Calculation Mode */
                    $isExistGlAccount = $this->glAccountRepo->findByOtherKey(array(
                        'company_id' => $company_id,
                        'LOWER(gl_code)=' => $item[7],
                    ));
                    if(empty($isExistGlAccount)){
                        $errorFlg++;
                        $errorMessage .= $errorTemplate("<strong>{$item[7]}</strong> ".lang("Deductions.upload.validation.notfound"));
                    }

                    $transactionId++;

                    /**Check Area */
                    $area = explode(",", $item[9]);
                    
                    if(!empty($area)){
                        $i = 1;
                        $j = 0;
                        $DeductionAreaLabel = array();
                        $areaError = array();
                        foreach ($area as $value) {
                            $error = false;
                            $checkIfexistsArea = $this->areaRepository->findByOtherKey(array(
                                'company_id' => $company_id, 
                                'name' => $value, 
                            ));

                            if(!empty($checkIfexistsArea)){
                                $DeductionArea[$areaIncrement]['process_id'] = $processId;
                                $DeductionArea[$areaIncrement]['transaction_id'] = $transactionId;
                                $DeductionArea[$areaIncrement]['seq_no'] = $i;
                                $DeductionArea[$areaIncrement]['area_type'] = '0';
                                $DeductionArea[$areaIncrement]['area_id'] = !empty($checkIfexistsArea) ? $checkIfexistsArea->work_unit_id : '';
                                $DeductionArea[$areaIncrement]['created_by'] = $this->S_NO_REG;
                                $DeductionArea[$areaIncrement]['created_dt'] = date('Y-m-d H:i:s');
                                $DeductionArea[$areaIncrement]['changed_by'] = $this->S_NO_REG;
                                $DeductionArea[$areaIncrement]['changed_dt'] = date('Y-m-d H:i:s');
                                $areaIncrement++;
                                $i++;

                                $DeductionAreaLabel[$j] = !empty($checkIfexistsArea) ? $checkIfexistsArea->name : '';
                            } else {
                                $errorFlg++;
                                $error = true;
                                $areaError[] = $value; 
                                $DeductionAreaLabel[$j] = $value;
                            }

                            $j++;
                        }

                        if(!empty($areaError)){
                            $errorMessage .= $errorTemplate("Area: (<strong>".implode(',',$areaError)."</strong>) ".lang("Deductions.upload.validation.notfound"));
                        }
                    }

                    /**Check Grup */
                    $grup = explode(",", $item[10]);
                    
                    if(!empty($grup)){
                        $j = 0;
                        $DeductionGrupLabel = array();
                        $areaGrupError = array();
                        foreach ($grup as $value) {
                            $error = false;
                            $checkIfexistsGrup = $this->systemRepository->findByOtherKey(array(
                                'system_type' => 'area_group', 
                                'LOWER(system_value_txt)=' => $value, 
                            ));

                            if(!empty($checkIfexistsGrup)){
                                $DeductionGrup[$grupIncrement]['process_id'] = $processId;
                                $DeductionGrup[$grupIncrement]['transaction_id'] = $transactionId;
                                $DeductionGrup[$grupIncrement]['seq_no'] = $i;
                                $DeductionGrup[$grupIncrement]['area_type'] = '1';
                                $DeductionGrup[$grupIncrement]['area_id'] = !empty($checkIfexistsGrup) ? $checkIfexistsGrup->system_code : '';
                                $DeductionGrup[$grupIncrement]['created_by'] = $this->S_NO_REG;
                                $DeductionGrup[$grupIncrement]['created_dt'] = date('Y-m-d H:i:s');
                                $DeductionGrup[$grupIncrement]['changed_by'] = $this->S_NO_REG;
                                $DeductionGrup[$grupIncrement]['changed_dt'] = date('Y-m-d H:i:s');
                                $grupIncrement++;
                                $i++;
                                
                                $DeductionGrupLabel[$j] = !empty($checkIfexistsGrup) ? $checkIfexistsGrup->system_value_txt : '';
                            } else {
                                $errorFlg++;
                                $areaGrupError[] = $value;
                                $error = true;
                                
                                $DeductionGrupLabel[$j] = $value;
                            }
                            
                            $j++;
                        }
                        
                        if(!empty($areaGrupError)){
                            $errorMessage .= $errorTemplate("Area Grup: (<strong>".implode(',',$areaGrupError)."</strong>) ".lang("Deductions.upload.validation.notfound"));
                        }
                    }
                    
                    /**Check Rules */
                    $rules = explode(",", $item[11]);
                    
                    if(!empty($rules)){
                        $i = 1;
                        $j = 0;
                        $DeductionRulesLabel = array();
                        $areaRulesError = array();
                        foreach ($rules as $value) {
                            $error = false;
                            $checkIfexistsRules = $this->areaRulesRepo->findByOtherKey(array(
                                'company_id' => $company_id, 
                                'LOWER(rules_code)=' => $value, 
                                'rules_type' => '1'
                            ));

                            if(!empty($checkIfexistsRules)){
                                $DeductionPayrolRules[$rulesIncrement]['process_id'] = $processId;
                                $DeductionPayrolRules[$rulesIncrement]['transaction_id'] = $transactionId;
                                $DeductionPayrolRules[$rulesIncrement]['seq_no'] = $i;
                                $DeductionPayrolRules[$rulesIncrement]['rules_id'] = !empty($checkIfexistsRules) ? $checkIfexistsRules->payroll_rules_id : '';
                                $DeductionPayrolRules[$rulesIncrement]['created_by'] = $this->S_NO_REG;
                                $DeductionPayrolRules[$rulesIncrement]['created_dt'] = date('Y-m-d H:i:s');
                                $DeductionPayrolRules[$rulesIncrement]['changed_by'] = $this->S_NO_REG;
                                $DeductionPayrolRules[$rulesIncrement]['changed_dt'] = date('Y-m-d H:i:s');
                                $rulesIncrement++;
                                $i++;
                                
                                $DeductionRulesLabel[$j] = !empty($checkIfexistsRules) ? $checkIfexistsRules->rules_code : '';
                            } else {
                                $errorFlg++;
                                $areaRulesError[] = $value;
                                $error = true;
                                
                                $DeductionRulesLabel[$j] = $value;
                            }
                            
                            $j++;
                        }
                        
                        if(!empty($areaRulesError)){
                            $errorMessage .= $errorTemplate("Payroll Rules: (<strong>".implode(',',$areaRulesError)."</strong>) ".lang("Deductions.upload.validation.notfound"));
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
                        'deduction_code' => $item[2],
                        'deduction_name' => $item[3],
                        'default_value' => $this->encrypt($item[4]),
                        'calculation_type' => !empty($isExistsCalculationType) ? $isExistsCalculationType->system_code : '-',
                        'calculation_mode' => !empty($isExistsCalculationMode) ? $isExistsCalculationMode->system_code : '-',
                        'gl_id' => (!empty($isExistGlAccount) ? $isExistGlAccount->gl_id : ''),
                        'effective_date' => std_date($item[8],'Y-m-d','Y-m-d'),
                        'effective_date_end' => '2999-12-31',
                        'list_area' => !empty($DeductionAreaLabel) ? implode(",",$DeductionAreaLabel) : '',
                        'list_group' => !empty($DeductionGrupLabel) ? implode(",",$DeductionGrupLabel) : '',
                        'list_rules' => !empty($DeductionRulesLabel) ? implode(",",$DeductionRulesLabel) : '',
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
            
            $this->tempDeductionRepo->delete(array('created_by' => $this->S_NO_REG));
            $this->tempDeductionRepo->insertBatch($results);
            
            $this->tempDeductionAreaRepo->delete(array('created_by' => $this->S_NO_REG, 'area_type' => '0'));
            $this->tempDeductionAreaRepo->insertBatch($DeductionArea);
            
            $this->tempDeductionAreaRepo->delete(array('created_by' => $this->S_NO_REG, 'area_type' => '1'));
            $this->tempDeductionAreaRepo->insertBatch($DeductionGrup);
            
            $this->tempDeductionRulesRepo->delete(array('created_by' => $this->S_NO_REG));
            $this->tempDeductionRulesRepo->insertBatch($DeductionPayrolRules);

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
        $totalInvalid = $this->tempDeductionRepo->countAllResults(array('process_id' => $payload['process_id'], 'valid_flg' => '0'));
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

        $this->serviceAction = '[MASTER_Deduction][UPLOADEXCEL]';

        $repository = $this;
        $error = null;
        
        $result = queryTransaction(function() use ($processId,$repository,$companyId) 
        {
            $saveHeader = $repository->tempDeductionRepo->importExcel($processId, $companyId);

            if($saveHeader){
                $repository->tempDeductionAreaRepo->importExcelArea($processId);
                $repository->tempDeductionAreaRepo->importExcelGrup($processId);
                $repository->tempDeductionRulesRepo->importExcelRules($processId);

                $repository->tempDeductionRepo->delete(array('process_id' => $processId));
                $repository->tempDeductionAreaRepo->delete(array('process_id' => $processId));
                $repository->tempDeductionRulesRepo->delete(array('process_id' => $processId));
            }

            return $saveHeader;
        }, 
        $error);

        if ($result === false) {
            return $this->dataError( 
                log : true,
                code : 500,
                data : null,
                message : 'Failed Upload Master Deductions --> ' . $error,
            );
        }
        
        return $this->dataSuccess( 
            log : true,
            code : 201,
            data : array ('redirect_link'=>'master_potongan/upload'),
            message : 'Successfully Upload Master Deductions', 
        );
        
    }
}