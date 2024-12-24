<?php

namespace App\Services\Master\Deductions;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Helpers\Datatable;
use App\Repositories\Master\Deductions\DeductionAreaRepository;
use App\Repositories\Master\Deductions\DeductionRepository;
use App\Repositories\Master\Deductions\DeductionRulesRepository;
use App\Repositories\Master\Area\AreaRepository;
use App\Repositories\Master\Area\AreaRulesRepository;
use App\Repositories\Master\CompanyRepository;
use App\Repositories\Master\GlAccounts\GlAccountRepository;
use App\Repositories\Master\MasterSystemRepository;
use App\Services\BaseService;
use App\Services\Logs\PayrollLogService;
use App\Services\Master\Deductions\Excel\DeductionInquiryService;
use DateTime;

class DeductionService extends BaseService{
    protected mixed $deductionRepository;
    protected mixed $deductionRepositoryArea;
    protected mixed $deductionPayrollRules;
    protected mixed $companyRepository;
    protected mixed $areaRepository;
    protected mixed $systemRepository;
    protected mixed $areaRulesRepo;
    protected mixed $glAccountRepo;
    protected $excelService;
    protected $payrollLogService;
    protected $functionId = 304;

    public function __construct()
    {
        parent::__construct();
        $this->deductionRepository = new DeductionRepository();
        $this->deductionRepositoryArea = new DeductionAreaRepository();
        $this->deductionPayrollRules = new DeductionRulesRepository();
        $this->excelService = new DeductionInquiryService();
        $this->payrollLogService = new PayrollLogService();
        $this->companyRepository = new CompanyRepository();
        $this->areaRepository = new AreaRepository();
        $this->systemRepository = new MasterSystemRepository();
        $this->areaRulesRepo = new AreaRulesRepository();
        $this->glAccountRepo = new GlAccountRepository();
    }

    /**
     * @var array $payload
     * @return array
     * ----------------------------------------------------
     * name : datatable()
     * desc : Service to loaded datatable
     */
    public function datatable(?array $payload) : array
    {
        $this->serviceAction = '[MASTER_DEDUCTION][INQUIRY]';

        // Configure datatable settings
        // --------------------------------
        $filters = function() use ($payload) {
            $filters = array();
            if(isset($payload['company_id'])) $filters['company_id'] = $payload['company_id'];
            if(isset($payload['work_unit_id'])) $filters['area_name_id'] = $payload['work_unit_id'];
            if(isset($payload['area_grup_id'])) $filters['group_name_id'] = $payload['area_grup_id'];

            return $filters;
        };
        
        $likeFilters = function() use ($payload) {
            $filters = array();
            if(isset($payload['keyword'])) $filters['deduction_name'] = $payload['keyword'];
            
            return $filters;
        };
        
        $formattedFields = function ($item) {
            return [
                "
                <div class='checkbox checkbox-custom'>
                    <input type='checkbox' class='deduction' id='deduction_{$item->deduction_id}' value='{$item->deduction_id}' onclick=\"selfChecked('checkAll', 'btn_edit_inquiry', 'btn_delete_inquiry', 'deduction')\"/>
                    <label for='deduction_{$item->deduction_id}'></label>
                </div>
                ",
                isEmpty($item->company_name),
                isEmpty($item->area_name),
                isEmpty($item->group_name),
                !empty($item->effective_date) ? std_date($item->effective_date, 'Y-m-d', 'd F Y') : '-',
                isEmpty($item->deduction_name),
                number($this->decrypt($item->default_value)),
                ($item->is_active == '' || $item->is_active == '0') ? 'No' : 'Yes', 
                isEmpty($item->created_by),
                labelDate(isEmpty($item->created_dt)),
                isEmpty($item->changed_by),
                labelDate(isEmpty($item->changed_dt)),
                isEmpty($item->deduction_id),
            ];
        };

        $order      = $payload['order'];
        $column     = $payload['columns'];
        $id_cols    = $order[0]['column'];

        $orderBy = " created_dt DESC, ";
        if (isset($column[$id_cols]['name'])) {
            $orderBy .= $column[$id_cols]['name'] . " " . $order[0]['dir'];
        }
        
        // Instance datatable class
        // --------------------------------
        $table = new Datatable($this->deductionRepository, $payload);
        $table->setFilters($filters);
        $table->setFiltersLike($likeFilters);
        $table->setOrderBy($orderBy);

        return $this->dataSuccess( 
            code : 200,
            data : $table->getRows(fn($items) => array_map($formattedFields, $items)),
            message : 'Successfully loaded datatable deduction data.', 
        );
    }

    /**
     * @var array $payload
     * @return array
     * -----------------------------------c-----------------
     * name: create($payload)
     * desc: Service to create new deduction data
     */
    public function create(?array $payload) : array
    {
        $this->serviceAction = '[MASTER_DEDUCTION][CREATE]';

        $repository = $this;
        $error = null;
        
        $result = queryTransaction(function() use ($payload,$repository) 
        {
            if($this->deductionRepository->checkDataExists(array(
                'company_id' => $payload['company_id'],
                'deduction_code' => $payload['deduction_code']
            )) > 0){
                throw new \Exception('Data already exists in our system, please send another request.');
            }

            /**
             * Deduction
             */
            $deduction = array();
            $deduction['company_id'] = $payload['company_id'];
            $deduction['deduction_code'] = $payload['deduction_code'];
            $deduction['deduction_name'] = $payload['deduction_name'];
            $deduction['default_value'] = $this->encrypt(decimalvalue($payload['default_value']));
            $deduction['calculation_type'] = $payload['calculation_type'];
            $deduction['calculation_mode'] = $payload['calculation_mode'];
            $deduction['effective_date'] = std_date($payload['effective_date']);
            $deduction['effective_date_end'] = '2999-12-31';
            $deduction['gl_id'] = $payload['gl_id'];
            $deduction['is_active'] = isset($payload['is_active']) ? '1' : '0';
            $deduction['created_by'] = $this->S_NO_REG;
            $deduction['created_dt'] = date('Y-m-d H:i:s');
            $deductionResult = $repository->deductionRepository->save($deduction);

            /**
             * Deduction Area
             */
            if(!empty($payload['area'])):
                $i = 0;
                $deductionArea = array();
                foreach ($payload['area'] as $area) {
                    $deductionArea[$i]['deduction_id'] = $deductionResult['id'];
                    $deductionArea[$i]['area_type'] = '0';
                    $deductionArea[$i]['area_id'] = $area;
                    $deductionArea[$i]['created_by'] = $this->S_NO_REG;
                    $deductionArea[$i]['created_dt'] = date('Y-m-d H:i:s');
                    $deductionArea[$i]['changed_by'] = $this->S_NO_REG;
                    $deductionArea[$i]['changed_dt'] = date('Y-m-d H:i:s');
                    $i++;
                }
                $repository->deductionRepositoryArea->insertBatch($deductionArea);
            endif;
            
            /**
             * Deduction Area Grup
             */
            if(!empty($payload['areagrup'])):
                $i = 0;
                $deductionArea = array();
                foreach ($payload['areagrup'] as $area) {
                    $deductionArea[$i]['deduction_id'] = $deductionResult['id'];
                    $deductionArea[$i]['area_type'] = '1';
                    $deductionArea[$i]['area_id'] = $area;
                    $deductionArea[$i]['created_by'] = $this->S_NO_REG;
                    $deductionArea[$i]['created_dt'] = date('Y-m-d H:i:s');
                    $deductionArea[$i]['changed_by'] = $this->S_NO_REG;
                    $deductionArea[$i]['changed_dt'] = date('Y-m-d H:i:s');
                    $i++;
                }
                $repository->deductionRepositoryArea->insertBatch($deductionArea);
            endif;
            
            /**
             * Deduction Payrole Rules
             */
            if(!empty($payload['payrollrules'])):
                $i = 0;
                $deductionArea = array();
                foreach ($payload['payrollrules'] as $payrollRules) {
                    $deductionArea[$i]['deduction_id'] = $deductionResult['id'];
                    $deductionArea[$i]['rules_id'] = $payrollRules;
                    $deductionArea[$i]['deduction_amount'] = 0;
                    $deductionArea[$i]['created_by'] = $this->S_NO_REG;
                    $deductionArea[$i]['created_dt'] = date('Y-m-d H:i:s');
                    $deductionArea[$i]['changed_by'] = $this->S_NO_REG;
                    $deductionArea[$i]['changed_dt'] = date('Y-m-d H:i:s');
                    $i++;
                }
                $repository->deductionPayrollRules->insertBatch($deductionArea);
            endif;

            return $deductionResult;
        }, 
        $error);

        if ($result === false) {
            return $this->dataError( 
                log : true,
                code : 500,
                data : null,
                message : $error,
            );
        }
        
        $this->payrollLogService->create(array(
            'function_id' => $this->functionId,
            'refference_id' => (string) $result['id'],
            'data_before' => '',
            'data_after' => '',
            'history_details' => $this->messageCreated(),
        ));
        
        return $this->dataSuccess( 
            log : true,
            code : 201,
            data : array ('redirect_link'=>'master_potongan/id/'.$result['id']),
            message : 'Successfully Created Master Deductions', 
        );
    }

    /**
     * @var array $payload
     * @return array
     * -----------------------------------c-----------------
     * name: create($payload)
     * desc: Service to create new deduction data
     */
    public function update(?array $payload) : array
    {
        $this->serviceAction = '[MASTER_DEDUCTION][UPDATE]';

        $repository = $this;
        $error = null;
        $deductionId = $payload['deduction_id'];
        $payrollLogBefore = $this->deductionRepository->getLogHistory(array('deduction_id' => $deductionId));
        
        $result = queryTransaction(function() use ($payload,$repository, &$deductionId) 
        {
            $deductionBefore = $repository->deductionRepository->findByOtherKey(array('deduction_id' => $payload['deduction_id']));

            if($this->deductionRepository->checkDataExists(array(
                'company_id' => $payload['company_id'],
                'deduction_code' => $payload['deduction_code'],
                'is_active' => '1'
            ), array(
                'deduction_id' => $payload['deduction_id'],
            )) > 0){
                throw new \Exception('Data already exists in our system, please send another request.');
            }

            /**
             * Deduction 
             */

            $deduction = array();
            $deduction['company_id'] = $payload['company_id'];
            $deduction['deduction_code'] = $payload['deduction_code'];
            $deduction['deduction_name'] = $payload['deduction_name'];
            $deduction['default_value'] = $this->encrypt(decimalvalue($payload['default_value']));
            $deduction['calculation_type'] = $payload['calculation_type'];
            $deduction['calculation_mode'] = $payload['calculation_mode'];
            $deduction['effective_date'] = std_date($payload['effective_date']);
            $deduction['effective_date_end'] = '2999-12-31';
            $deduction['gl_id'] = $payload['gl_id'];
            $deduction['is_active'] = isset($payload['is_active']) ? '1' : '0';
            $deduction['created_by'] = $this->S_NO_REG;
            $deduction['created_dt'] = date('Y-m-d H:i:s');
            $deduction['changed_by'] = $this->S_NO_REG;
            $deduction['changed_dt'] = date('Y-m-d H:i:s');
            
            if(
                $deductionBefore->effective_date != std_date($payload['effective_date']) || 
                $this->decrypt($deductionBefore->default_value) != decimalvalue($payload['default_value'])
            )
            {
                $givenDate = std_date($payload['effective_date']);
                $effective_date_end = new DateTime($givenDate);
                $effective_date_end->modify('-1 day');
    
                $deductionPrev = array();
                $deductionPrev['effective_date_end'] = $effective_date_end->format('Y-m-d');
                $deductionPrev['is_active'] = '0';
                $deductionPrev['changed_by'] = $this->S_NO_REG;
                $deductionPrev['changed_dt'] = date('Y-m-d H:i:s');
                $repository->deductionRepository->update($deductionPrev, array('deduction_id' => $payload['deduction_id']));
    
                $deductionResult = $repository->deductionRepository->save($deduction);
                $deductionId     = $deductionResult['id'];
            } 
            else 
            {
                $deductionResult = $repository->deductionRepository->update($deduction, array('deduction_id' => $payload['deduction_id']));
            }

            /**
             * Deduction Area
             */
            if(!empty($payload['area'])):
                $i = 0;
                $deductionArea = array();
                foreach ($payload['area'] as $area) {
                    $deductionArea[$i]['deduction_id'] = $deductionId;
                    $deductionArea[$i]['area_type'] = '0';
                    $deductionArea[$i]['area_id'] = $area;
                    $deductionArea[$i]['created_by'] = $this->S_NO_REG;
                    $deductionArea[$i]['created_dt'] = date('Y-m-d H:i:s');
                    $deductionArea[$i]['changed_by'] = $this->S_NO_REG;
                    $deductionArea[$i]['changed_dt'] = date('Y-m-d H:i:s');
                    $i++;
                }
                $repository->deductionRepositoryArea->delete(array('deduction_id' => $deductionId, 'area_type' => '0'));	
                $repository->deductionRepositoryArea->insertBatch($deductionArea);
            endif;
            
            /**
             * Deduction Grup
             */
            if(!empty($payload['areagrup'])):
                $i = 0;
                $deductionArea = array();
                foreach ($payload['areagrup'] as $area) {
                    $deductionArea[$i]['deduction_id'] = $deductionId;
                    $deductionArea[$i]['area_type'] = '1';
                    $deductionArea[$i]['area_id'] = $area;
                    $deductionArea[$i]['created_by'] = $this->S_NO_REG;
                    $deductionArea[$i]['created_dt'] = date('Y-m-d H:i:s');
                    $deductionArea[$i]['changed_by'] = $this->S_NO_REG;
                    $deductionArea[$i]['changed_dt'] = date('Y-m-d H:i:s');
                    $i++;
                }
                $repository->deductionRepositoryArea->delete(array('deduction_id' => $deductionId, 'area_type' => '1'));	
                $repository->deductionRepositoryArea->insertBatch($deductionArea);
            endif;
            
            /**
             * Deduction Payrole Rules
             */
            if(!empty($payload['payrollrules'])):
                $i = 0;
                $deductionArea = array();
                foreach ($payload['payrollrules'] as $payrollRules) {
                    $deductionArea[$i]['deduction_id'] = $deductionId;
                    $deductionArea[$i]['rules_id'] = $payrollRules;
                    $deductionArea[$i]['deduction_amount'] = 0;
                    $deductionArea[$i]['created_by'] = $this->S_NO_REG;
                    $deductionArea[$i]['created_dt'] = date('Y-m-d H:i:s');
                    $deductionArea[$i]['changed_by'] = $this->S_NO_REG;
                    $deductionArea[$i]['changed_dt'] = date('Y-m-d H:i:s');
                    $i++;
                }
                $repository->deductionPayrollRules->delete(array('deduction_id' => $deductionId));	
                $repository->deductionPayrollRules->insertBatch($deductionArea);
            endif;

            return $deductionResult;
        }, 
        $error);

        if ($result === false) {
            return $this->dataError( 
                log : true,
                code : 500,
                data : null,
                message : $error,
            );
        }

        $payrollLogAfter = $this->deductionRepository->getLogHistory(array('deduction_id' => $deductionId));

        $this->payrollLogService->create(array(
            'function_id' => $this->functionId,
            'refference_id' => (string) $deductionId,
            'data_before' => $payrollLogBefore,
            'data_after' => $payrollLogAfter,
            'history_details' => '',
        ));
        
        
        return $this->dataSuccess( 
            log : true,
            code : 201,
            data : array ('redirect_link'=>'master_potongan/id/'.$deductionId),
            message : 'Successfully Updated Master Deductions', 
        );
    }

    public function getByKey(string $id){
        $this->serviceAction = '[DEDUCTION][GET_BY_KEY]';

        $data = $this->deductionRepository->findByOtherKey(array('deduction_id' => $id));
        $data->default_value = $this->decrypt($data->default_value);

        return $this->dataSuccess( 
            log : true,
            code : 200,
            data : $data,
            message : 'Successfully Get Area Data', 
        );
    }

    /**
     * @var request $params
     * @return array
     * ----------------------------------------------------
     * name: remove($params)
     * desc: Service to delete system data
     */
    public function remove(?array $body) : array
    {
        $this->serviceAction = '[MASTER_DEDUCTION][DELETE]';

        $repository = $this;
        $error      = null;
        
        $payrollLogData = $repository->deductionRepository->getLogHistory(array('deduction_id' => $body['deduction_id']));

        $result     = queryTransaction(function() use ($body, $repository,) {
            $repository->deductionRepositoryArea->delete(array('deduction_id' => $body['deduction_id']));
            $repository->deductionPayrollRules->delete(array('deduction_id' => $body['deduction_id']));
            $deduction = $repository->deductionRepository->delete(array('deduction_id' => $body['deduction_id']));

            return $deduction;
        }, $error);

        if ($result === false) {
            return $this->dataError( 
                        log : true,
                        code : 500,
                        data : null,
                        message : 'Failed Deleted Deduction' . ' --> ' . $error,
                    );
        }
        
        $this->payrollLogService->create(array(
            'function_id' => $this->functionId,
            'refference_id' => (string) $body['deduction_id'],
            'data_before' => $payrollLogData,
            'data_after' => $payrollLogData,
            'history_details' => $this->messsagDeleted(),
        ));

        return $this->dataSuccess( 
            log : true,
            code : 204,
            data : array('redirect_link' => 'master_potongan'), 
            message : 'Successfully Deleted Deduction', 
        );
    }

    /**
     * @var request $params
     * @return array
     * ----------------------------------------------------
     * name: removeSelected($params)
     * desc: Service to delete selected deduction data
     */
    public function removeSelected($body) : array
    {
        $this->serviceAction = '[MASTER_DEDUCTION][DELETE_SELECTED]';

        $repository = $this;
        $error      = null;
        
        $result     = queryTransaction(function() use ($body, $repository,) {
            $ids = explode(",", $body['ids']);

            if(!empty($ids)){
                foreach ($ids as $value) {
                    $repository->deductionRepositoryArea->delete(array('deduction_id' => $value));
                    $repository->deductionPayrollRules->delete(array('deduction_id' => $value));
                    $deduction = $repository->deductionRepository->delete(array('deduction_id' => $value));
                }
            }

            return $deduction;
        }, $error);

        if ($result === false) {
            return $this->dataError( 
                        log : true,
                        code : 500,
                        data : null,
                        message : 'Failed Deleted Selected Deduction' . ' --> ' . $error,
                    );
        }

        return $this->dataSuccess( 
            log : true,
            code : 204,
            data : array('redirect_link' => 'master_potongan'), 
            message : 'Successfully Deleted Selected Deduction', 
        );
    }

    /**
     * @var request $params
     * @return array
     * ----------------------------------------------------
     * name: downloadExcel($payload)
     * desc: Service to download excel
     */
    public function downloadExcel(array $payload)
    {
        try {
            $filters = array();
            if(isset($payload['company_id']))   $filters['company_id'] = $payload['company_id'];
            if(isset($payload['work_unit_id'])) $filters['area_name_id'] = $payload['work_unit_id'];
            if(isset($payload['area_grup_id'])) $filters['group_name_id'] = $payload['area_grup_id'];
    
            $likeFilters = array();
            if(isset($payload['keyword'])) $filters['deduction_name'] = $payload['keyword'];
            
            $data = $this->deductionRepository->findAllFilteredRecords($filters, $likeFilters, $this->excelService->fields);
            
            $this->excelService->setFileName("MasterDeduction_".date('YmdHis')."_".time().".xlsx");
            $this->excelService->setWorksheetName("List of Master Data Deduction");
            $filePath = $this->excelService->generate($data);

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