<?php

namespace App\Services\Master\Allowances;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Helpers\Datatable;
use App\Repositories\Master\GlAccounts\GlAccountRepository;
use App\Repositories\Master\Allowances\AllowanceAreaRepository;
use App\Repositories\Master\Allowances\AllowanceRepository;
use App\Repositories\Master\Allowances\AllowanceRulesRepository;
use App\Repositories\Master\Area\AreaRepository;
use App\Repositories\Master\Area\AreaRulesRepository;
use App\Repositories\Master\CompanyRepository;
use App\Repositories\Master\MasterSystemRepository;
use App\Services\BaseService;
use App\Services\Logs\PayrollLogService;
use App\Services\Master\Allowances\Excel\AllowanceInquiryService;
use DateTime;

class AllowanceService extends BaseService{
    protected mixed $allowanceRepository;
    protected mixed $allowanceRepositoryArea;
    protected mixed $allowancePayrollRules;
    protected mixed $companyRepository;
    protected mixed $areaRepository;
    protected mixed $systemRepository;
    protected mixed $areaRulesRepo;
    protected mixed $glAccountRepo;
    protected $excelService;
    protected $payrollLogService;
    protected $functionId = 302;

    public function __construct()
    {
        parent::__construct();
        $this->allowanceRepository = new AllowanceRepository();
        $this->allowanceRepositoryArea = new AllowanceAreaRepository();
        $this->allowancePayrollRules = new AllowanceRulesRepository();
        $this->excelService = new AllowanceInquiryService();
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
        $this->serviceAction = '[MASTER_ALLOWANCE][INQUIRY]';

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
            if(isset($payload['keyword'])) $filters['allowance_name'] = $payload['keyword'];
            
            return $filters;
        };
        
        $formattedFields = function ($item) {
            return [
                "
                <div class='checkbox checkbox-custom'>
                    <input type='checkbox' class='allowance' id='allowance_{$item->allowance_id}' value='{$item->allowance_id}' onclick=\"selfChecked('checkAll', 'btn_edit_inquiry', 'btn_delete_inquiry', 'allowance')\"/>
                    <label for='allowance_{$item->allowance_id}'></label>
                </div>
                ",
                isEmpty($item->company_name),
                isEmpty($item->area_name),
                isEmpty($item->group_name),
                !empty($item->effective_date) ? std_date($item->effective_date, 'Y-m-d', 'd F Y') : '-',
                isEmpty($item->allowance_name),
                number($this->decrypt($item->default_value)),
                ($item->is_active == '' || $item->is_active == '0') ? 'No' : 'Yes', 
                isEmpty($item->created_by),
                labelDate(isEmpty($item->created_dt)),
                isEmpty($item->changed_by),
                labelDate(isEmpty($item->changed_dt)),
                isEmpty($item->allowance_id),
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
        $table = new Datatable($this->allowanceRepository, $payload);
        $table->setFilters($filters);
        $table->setFiltersLike($likeFilters);
        $table->setOrderBy($orderBy);

        return $this->dataSuccess( 
            code : 200,
            data : $table->getRows(fn($items) => array_map($formattedFields, $items)),
            message : 'Successfully loaded datatable allowances data.', 
        );
    }

    /**
     * @var array $payload
     * @return array
     * -----------------------------------------------------
     * name: create($payload)
     * desc: Service to create new allowance data
     */
    public function create(?array $payload) : array
    {
        $this->serviceAction = '[MASTER_ALLOWANCE][CREATE]';

        $repository = $this;
        $error = null;
        
        $result = queryTransaction(function() use ($payload,$repository) 
        {
            if($this->allowanceRepository->checkDataExists(array(
                'company_id' => $payload['company_id'],
                'allowance_code' => $payload['allowance_code']
            )) > 0){
                throw new \Exception('Data already exists in our system, please send another request.');
            }

            /**
             * Allowance
             */
            $allowance = array();
            $allowance['company_id'] = $payload['company_id'];
            $allowance['allowance_code'] = $payload['allowance_code'];
            $allowance['allowance_name'] = $payload['allowance_name'];
            $allowance['default_value'] = $this->encrypt(decimalvalue($payload['default_value']));
            $allowance['minimum_working_period'] = $payload['minimum_working_period'];
            $allowance['calculation_type'] = $payload['calculation_type'];
            $allowance['calculation_mode'] = $payload['calculation_mode'];
            $allowance['effective_date'] = std_date($payload['effective_date']);
            $allowance['effective_date_end'] = '2999-12-31';
            $allowance['gl_id'] = $payload['gl_id'];
            $allowance['is_active'] = isset($payload['is_active']) ? '1' : '0';
            $allowance['created_by'] = $this->S_NO_REG;
            $allowance['created_dt'] = date('Y-m-d H:i:s');
            $allowanceResult = $repository->allowanceRepository->save($allowance);

            /**
             * Allowance Area
             */
            if(!empty($payload['area'])):
                $i = 0;
                $allowanceArea = array();
                foreach ($payload['area'] as $area) {
                    $allowanceArea[$i]['allowance_id'] = $allowanceResult['id'];
                    $allowanceArea[$i]['area_type'] = '0';
                    $allowanceArea[$i]['area_id'] = $area;
                    $allowanceArea[$i]['created_by'] = $this->S_NO_REG;
                    $allowanceArea[$i]['created_dt'] = date('Y-m-d H:i:s');
                    $allowanceArea[$i]['changed_by'] = $this->S_NO_REG;
                    $allowanceArea[$i]['changed_dt'] = date('Y-m-d H:i:s');
                    $i++;
                }
                $repository->allowanceRepositoryArea->insertBatch($allowanceArea);
            endif;
            
            /**
             * Allowance Area Grup
             */
            if(!empty($payload['areagrup'])):
                $i = 0;
                $allowanceArea = array();
                foreach ($payload['areagrup'] as $area) {
                    $allowanceArea[$i]['allowance_id'] = $allowanceResult['id'];
                    $allowanceArea[$i]['area_type'] = '1';
                    $allowanceArea[$i]['area_id'] = $area;
                    $allowanceArea[$i]['created_by'] = $this->S_NO_REG;
                    $allowanceArea[$i]['created_dt'] = date('Y-m-d H:i:s');
                    $allowanceArea[$i]['changed_by'] = $this->S_NO_REG;
                    $allowanceArea[$i]['changed_dt'] = date('Y-m-d H:i:s');
                    $i++;
                }
                $repository->allowanceRepositoryArea->insertBatch($allowanceArea);
            endif;
            
            /**
             * Allowance Payrole Rules
             */
            if(!empty($payload['payrollrules'])):
                $i = 0;
                $allowanceArea = array();
                foreach ($payload['payrollrules'] as $payrollRules) {
                    $allowanceArea[$i]['allowance_id'] = $allowanceResult['id'];
                    $allowanceArea[$i]['rules_id'] = $payrollRules;
                    $allowanceArea[$i]['deduction_amount'] = 0;
                    $allowanceArea[$i]['created_by'] = $this->S_NO_REG;
                    $allowanceArea[$i]['created_dt'] = date('Y-m-d H:i:s');
                    $allowanceArea[$i]['changed_by'] = $this->S_NO_REG;
                    $allowanceArea[$i]['changed_dt'] = date('Y-m-d H:i:s');
                    $i++;
                }
                $repository->allowancePayrollRules->insertBatch($allowanceArea);
            endif;

            return $allowanceResult;
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
            data : array ('redirect_link'=>'master_tunjangan/id/'.$result['id']),
            message : 'Successfully Created Master Allowances', 
        );
    }

    /**
     * @var array $payload
     * @return array
     * -----------------------------------------------------
     * name: create($payload)
     * desc: Service to create new allowance data
     */
    public function update(?array $payload) : array
    {
        $this->serviceAction = '[MASTER_ALLOWANCE][UPDATE]';

        $repository = $this;
        $error = null;
        $allowanceId = $payload['allowance_id'];
        $payrollLogBefore = $this->allowanceRepository->getLogHistory(array('allowance_id' => $allowanceId));

        $result = queryTransaction(function() use ($payload,$repository,&$allowanceId) 
        {
                $allowanceBefore = $repository->allowanceRepository->findByCustomTable(array('allowance_id' => $payload['allowance_id']));

                if($this->allowanceRepository->checkDataExists(array(
                    'company_id' => $payload['company_id'],
                    'allowance_code' => $payload['allowance_code'],
                    'is_active' => '1'
                ), array(
                    'allowance_id' => $payload['allowance_id']
                )) > 0){
                    throw new \Exception('Data already exists in our system, please send another request.');
                }

                /**
                 * Allowance 
                 */
    
                $allowance = array();
                $allowance['company_id'] = $payload['company_id'];
                $allowance['allowance_code'] = $payload['allowance_code'];
                $allowance['allowance_name'] = $payload['allowance_name'];
                $allowance['default_value'] = $this->encrypt(decimalvalue($payload['default_value']));
                $allowance['minimum_working_period'] = $payload['minimum_working_period'];
                $allowance['calculation_type'] = $payload['calculation_type'];
                $allowance['calculation_mode'] = $payload['calculation_mode'];
                $allowance['effective_date'] = std_date($payload['effective_date']);
                $allowance['effective_date_end'] = '2999-12-31';
                $allowance['gl_id'] = $payload['gl_id'];
                $allowance['is_active'] = isset($payload['is_active']) ? '1' : '0';
                $allowance['created_by'] = $this->S_NO_REG;
                $allowance['created_dt'] = date('Y-m-d H:i:s');
                $allowance['changed_by'] = $this->S_NO_REG;
                $allowance['changed_dt'] = date('Y-m-d H:i:s');
                
                if(
                    $allowanceBefore->effective_date != std_date($payload['effective_date']) || 
                    $this->decrypt($allowanceBefore->default_value) != decimalvalue($payload['default_value'])
                )
                {
                    $givenDate = std_date($payload['effective_date']);
                    $effective_date_end = new DateTime($givenDate);
                    $effective_date_end->modify('-1 day');
        
                    $allowancePrev = array();
                    $allowancePrev['effective_date_end'] = $effective_date_end->format('Y-m-d');
                    $allowancePrev['is_active'] = '0';
                    $allowancePrev['changed_by'] = $this->S_NO_REG;
                    $allowancePrev['changed_dt'] = date('Y-m-d H:i:s');
                    $repository->allowanceRepository->update($allowancePrev, array('allowance_id' => $payload['allowance_id']));
        
                    $allowanceResult = $repository->allowanceRepository->save($allowance);
                    $allowanceId     = $allowanceResult['id'];
                } 
                else 
                {
                    $allowanceResult = $repository->allowanceRepository->update($allowance, array('allowance_id' => $payload['allowance_id']));
                }
    
                /**
                 * Allowance Area
                 */
                if(!empty($payload['area'])):
                    $i = 0;
                    $allowanceArea = array();
                    foreach ($payload['area'] as $area) {
                        $allowanceArea[$i]['allowance_id'] = $allowanceId;
                        $allowanceArea[$i]['area_type'] = '0';
                        $allowanceArea[$i]['area_id'] = $area;
                        $allowanceArea[$i]['created_by'] = $this->S_NO_REG;
                        $allowanceArea[$i]['created_dt'] = date('Y-m-d H:i:s');
                        $allowanceArea[$i]['changed_by'] = $this->S_NO_REG;
                        $allowanceArea[$i]['changed_dt'] = date('Y-m-d H:i:s');
                        $i++;
                    }
                    $repository->allowanceRepositoryArea->delete(array('allowance_id' => $allowanceId, 'area_type' => '0'));	
                    $repository->allowanceRepositoryArea->insertBatch($allowanceArea);
                endif;
                
                /**
                 * Allowance Grup
                 */
                if(!empty($payload['areagrup'])):
                    $i = 0;
                    $allowanceArea = array();
                    foreach ($payload['areagrup'] as $area) {
                        $allowanceArea[$i]['allowance_id'] = $allowanceId;
                        $allowanceArea[$i]['area_type'] = '1';
                        $allowanceArea[$i]['area_id'] = $area;
                        $allowanceArea[$i]['created_by'] = $this->S_NO_REG;
                        $allowanceArea[$i]['created_dt'] = date('Y-m-d H:i:s');
                        $allowanceArea[$i]['changed_by'] = $this->S_NO_REG;
                        $allowanceArea[$i]['changed_dt'] = date('Y-m-d H:i:s');
                        $i++;
                    }
                    $repository->allowanceRepositoryArea->delete(array('allowance_id' => $allowanceId, 'area_type' => '1'));	
                    $repository->allowanceRepositoryArea->insertBatch($allowanceArea);
                endif;
                
                /**
                 * Allowance Payrole Rules
                 */
                if(!empty($payload['payrollrules'])):
                    $i = 0;
                    $allowanceArea = array();
                    foreach ($payload['payrollrules'] as $payrollRules) {
                        $allowanceArea[$i]['allowance_id'] = $allowanceId;
                        $allowanceArea[$i]['rules_id'] = $payrollRules;
                        $allowanceArea[$i]['deduction_amount'] = '0';
                        $allowanceArea[$i]['created_by'] = $this->S_NO_REG;
                        $allowanceArea[$i]['created_dt'] = date('Y-m-d H:i:s');
                        $allowanceArea[$i]['changed_by'] = $this->S_NO_REG;
                        $allowanceArea[$i]['changed_dt'] = date('Y-m-d H:i:s');
                        $i++;
                    }
                    $repository->allowancePayrollRules->delete(array('allowance_id' => $allowanceId));	
                    $repository->allowancePayrollRules->insertBatch($allowanceArea);
                endif;
    
                return $allowanceResult;
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

        $payrollLogAfter = $this->allowanceRepository->getLogHistory(array('allowance_id' => $allowanceId));

        $this->payrollLogService->create(array(
            'function_id' => $this->functionId,
            'refference_id' => (string) $allowanceId,
            'data_before' => $payrollLogBefore,
            'data_after' => $payrollLogAfter,
            'history_details' => '',
        ));
        
        return $this->dataSuccess( 
            log : true,
            code : 201,
            data : array ('redirect_link'=>'master_tunjangan/id/'.$allowanceId),
            message : 'Successfully Updated Master Allowances', 
        );
    }

    /**
     * @var string $id
     * @return array
     * ----------------------------------------------------
     * name: getByKey($id)
     * desc: Service to get Data By Key
     */
    public function getByKey(string $id){
        $this->serviceAction = '[ALLOWANCE][GET_BY_KEY]';

        $data = $this->allowanceRepository->findByOtherKey(array('allowance_id' => $id));
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
        $this->serviceAction = '[MASTER_ALLOWANCE][DELETE]';

        $repository = $this;
        $error      = null;
        
        $payrollLogData = $repository->allowanceRepository->getLogHistory(array('allowance_id' => $body['allowance_id']));

        $result     = queryTransaction(function() use ($body, $repository,) {
            
            $repository->allowanceRepositoryArea->delete(array('allowance_id' => $body['allowance_id']));
            $repository->allowancePayrollRules->delete(array('allowance_id' => $body['allowance_id']));
            $allowance = $repository->allowanceRepository->delete(array('allowance_id' => $body['allowance_id']));

            return $allowance;
        }, $error);

        if ($result === false) {
            return $this->dataError( 
                        log : true,
                        code : 500,
                        data : null,
                        message : 'Failed Deleted Allowance' . ' --> ' . $error,
                    );
        }
        
        $this->payrollLogService->create(array(
            'function_id' => $this->functionId,
            'refference_id' => (string) $body['allowance_id'],
            'data_before' => $payrollLogData,
            'data_after' => $payrollLogData,
            'history_details' => $this->messsagDeleted(),
        ));

        return $this->dataSuccess( 
            log : true,
            code : 204,
            data : array('redirect_link' => 'master_tunjangan'), 
            message : 'Successfully Deleted Allowance', 
        );
    }

    /**
     * @var request $params
     * @return array
     * ----------------------------------------------------
     * name: removeSelected($params)
     * desc: Service to delete selected allowance data
     */
    public function removeSelected($body) : array
    {
        $this->serviceAction = '[MASTER_ALLOWANCE][DELETE_SELECTED]';

        $repository = $this;
        $error      = null;
        
        $result     = queryTransaction(function() use ($body, $repository,) {
            $ids = explode(",", $body['ids']);

            if(!empty($ids)){
                foreach ($ids as $value) {
                    $repository->allowanceRepositoryArea->delete(array('allowance_id' => $value));
                    $repository->allowancePayrollRules->delete(array('allowance_id' => $value));
                    $allowance = $repository->allowanceRepository->delete(array('allowance_id' => $value));
                }
            }

            return $allowance;
        }, $error);

        if ($result === false) {
            return $this->dataError( 
                        log : true,
                        code : 500,
                        data : null,
                        message : 'Failed Deleted Selected Allowance' . ' --> ' . $error,
                    );
        }

        return $this->dataSuccess( 
            log : true,
            code : 204,
            data : array('redirect_link' => 'master_tunjangan'), 
            message : 'Successfully Deleted Selected Allowance', 
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
            if(isset($payload['keyword'])) $filters['allowance_name'] = $payload['keyword'];
            
            $data = $this->allowanceRepository->findAllFilteredRecords($filters, $likeFilters, $this->excelService->fields);
            
            $this->excelService->setFileName("MasterAlowance_".date('YmdHis')."_".time().".xlsx");
            $this->excelService->setWorksheetName("List of Master Data Allowances");
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