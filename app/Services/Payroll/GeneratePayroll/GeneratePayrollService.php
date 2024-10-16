<?php

namespace App\Services\Payroll\GeneratePayroll;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Services\BaseService;
use App\Helpers\Datatable;
use App\Repositories\Payroll\GeneratePayroll\GeneratePayrollEmployeeRepository;
use App\Repositories\Payroll\GeneratePayroll\GeneratePayrollRepository;
use App\Services\Payroll\GeneratePayroll\Excel\GeneratePayrollInquiryService;

class GeneratePayrollService extends BaseService{
    protected $functionId = 501;
    protected $generatePayrollRepository;
    protected $generatePayrollEmployeeRepository;
    protected $excelService;
    
    public function __construct()
    {
        parent::__construct();
        $this->generatePayrollRepository = new GeneratePayrollRepository();
        $this->generatePayrollEmployeeRepository = new GeneratePayrollEmployeeRepository();
        $this->excelService = new GeneratePayrollInquiryService();
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
        $this->serviceAction = '[GENERATEPAYROLL][INQUIRY]';

        // Configure datatable settings
        // --------------------------------
        $filters = function() use ($payload) {
            $filters = array();
            if(isset($payload['company_id'])) $filters['company_id'] = $payload['company_id'];
            if(isset($payload['work_unit_id'])) $filters['work_unit_id'] = $payload['work_unit_id'];
            if(isset($payload['role_id'])) $filters['role_id'] = $payload['role_id'];

            return $filters;
        };
        
        $likeFilters = function() use ($payload) {
            $filters = array();
            if(isset($payload['keyword'])) $filters['payroll_title'] = $payload['keyword'];
            if(isset($payload['keyword'])) $filters['company_name'] = $payload['keyword'];
            if(isset($payload['keyword'])) $filters['work_unit_name'] = $payload['keyword'];
            if(isset($payload['keyword'])) $filters['role_name'] = $payload['keyword'];
            if(isset($payload['keyword'])) $filters['created_by'] = $payload['keyword'];
            if(isset($payload['keyword'])) $filters['changed_by'] = $payload['keyword'];
            
            return $filters;
        };
        
        $formattedFields = function ($item) {
            return [
                "
                <div class='checkbox checkbox-custom'>
                    <input type='checkbox' class='payroll_transaction' id='payroll_transaction_{$item->payroll_transaction_id}' value='{$item->payroll_transaction_id}' onclick=\"selfChecked('checkAll', 'btn_edit_inquiry', 'btn_delete_inquiry', 'payroll_transaction')\"/>
                    <label for='payroll_transaction_{$item->payroll_transaction_id}'></label>
                </div>
                ",
                isEmpty($item->company_name),
                isEmpty($item->work_unit_name),
                isEmpty($item->role_name),
                isEmpty(std_date($item->payroll_period,'Y-m','M Y')),
                isEmpty($item->payroll_title),
                isEmpty($item->total_employee),
                isEmpty($item->total_allowances),
                isEmpty($item->total_deductions),
                isEmpty($item->total_bruto),
                isEmpty($item->total_thp),
                isEmpty($item->process_flg),
                isEmpty($item->created_by),
                labelDate(isEmpty($item->created_dt)),
                isEmpty($item->changed_by),
                labelDate(isEmpty($item->changed_dt)),
                $item->payroll_transaction_id,
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
        $table = new Datatable($this->generatePayrollRepository, $payload);
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
        $this->serviceAction = '[GENERATE_PAYROLL][CREATE]';

        $repository = $this;
        $error = null;
        
        $result = queryTransaction(function() use ($payload,$repository) 
        {
            $employee_list = isset($payload['employee_list']) ? (!empty($payload['employee_list']) ? json_decode($payload['employee_list']) : array()) : array();
            
            $conditions = array();
            if(isset($payload['company_id'])){
                if(!empty($payload['company_id'])){
                    $conditions['company_id'] = $payload['company_id'];
                }
            }
            
            if(isset($payload['work_unit_id'])){
                if(!empty($payload['work_unit_id'])){
                    $conditions['work_unit_id'] = $payload['work_unit_id'];
                }
            }
            
            if(isset($payload['role_id'])){
                if(!empty($payload['role_id'])){
                    $conditions['role_id'] = $payload['role_id'];
                }
            }
            
            if(isset($payload['payroll_period'])){
                if(!empty($payload['payroll_period'])){
                    $conditions['payroll_period'] = std_date($payload['payroll_period'],'m/Y','Y-m');
                }
            }

            $checkIfDataExists = $repository->generatePayrollRepository->checkDataExists(array_merge($conditions, array('process_flg' => '1')));

            if($checkIfDataExists > 0){
                throw new \Exception('Data already exists in our system, please send another request.');
            }

            $getAllGeneratePayroll = $this->generatePayrollRepository->findAllWithLikeFilters(array_merge($conditions, array('process_flg' => '0')));
            $getAllTransactionId = array_map(function($item){
                return $item->payroll_transaction_id;
            },$getAllGeneratePayroll);
            
            $repository->generatePayrollEmployeeRepository->deleteByCondition("payroll_transaction_id IN (".implode(",",$getAllTransactionId).")");
            $repository->generatePayrollRepository->delete($conditions);

            $generatePayroll = array();
            $generatePayroll['company_id'] = $payload['company_id'];
            if(isset($payload['work_unit_id'])){
                $generatePayroll['work_unit_id'] = $payload['work_unit_id'];
            }
            if(isset($payload['role_id'])){
                $generatePayroll['role_id'] = $payload['role_id'];
            }
            $generatePayroll['payroll_title'] = $payload['description'];
            $generatePayroll['payroll_period'] = std_date($payload['payroll_period'], 'm/Y', 'Y-m');
            $generatePayroll['period_start'] = std_date($payload['cut_off_start']);
            $generatePayroll['period_end'] = std_date($payload['cut_off_end']);
            $generatePayroll['period_start'] = std_date($payload['cut_off_start']);
            $generatePayroll['attendance_period_start'] = std_date($payload['absence_period_start']);
            $generatePayroll['attendance_period_end'] = std_date($payload['absence_period_end']);
            $generatePayroll['total_employee'] = (string) count($employee_list);
            $generatePayroll['process_flg'] = '0';

            if(isset($payload['payroll_generate_options_1'])){
                if(!empty($payload['payroll_generate_options_1'])){
                    $generatePayrol['deduction_flg'] = '1';
                }
            }
            
            if(isset($payload['payroll_generate_options_2'])){
                if(!empty($payload['payroll_generate_options_2'])){
                    $generatePayrol['allowance_flg'] = '1';
                }
            }
            
            if(isset($payload['payroll_generate_options_3'])){
                if(!empty($payload['payroll_generate_options_3'])){
                    $generatePayrol['pph21_flg'] = '1';
                }
            }
            
            if(isset($payload['payroll_generate_options_4'])){
                if(!empty($payload['payroll_generate_options_4'])){
                    $generatePayrol['attendance_deduction_flg'] = '1';
                }
            }

            $generatePayroll['changed_dt'] = date('Y-m-d H:i:s');
            $generatePayroll['created_dt'] = date('Y-m-d H:i:s');
            $generatePayroll['created_by'] = (string) $this->S_NO_REG;
            $generatePayroll['changed_by'] = (string) $this->S_NO_REG;

            $generatePayrollResult = $repository->generatePayrollRepository->save($generatePayroll);

            if(!$generatePayrollResult){
                throw new \Exception('Failed when generate payroll, please check your request again.');
            }

            $generatePayrolEmployee = array();
            if(!empty($employee_list)){
                $i = 0;
                foreach ($employee_list as $key => $value) {
                    $generatePayrolEmployee[$i]['payroll_transaction_id'] = $generatePayrollResult['id'];
                    $generatePayrolEmployee[$i]['employee_id'] = $value->employee_id;
                    $generatePayrolEmployee[$i]['total_workday'] = $value->workDay;
                    $generatePayrolEmployee[$i]['changed_dt'] = date('Y-m-d H:i:s');
                    $generatePayrolEmployee[$i]['created_dt'] = date('Y-m-d H:i:s');
                    $generatePayrolEmployee[$i]['created_by'] = (string) $this->S_NO_REG;
                    $generatePayrolEmployee[$i]['changed_by'] = (string) $this->S_NO_REG;
                    $i++;
                }
            }

            $generatePayrolEmployee = $this->generatePayrollEmployeeRepository->insertBatch($generatePayrolEmployee);

            if(!$generatePayrolEmployee){
                throw new \Exception('Failed when generate payroll, please check your request again.');
            }

            return true;
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
        
        return $this->dataSuccess( 
            log : true,
            code : 201,
            data : array ('redirect_link'=>'generate_payroll/'),
            message : 'Successfully Generate Payroll', 
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
            $filters = function() use ($payload) {
                $filters = array();
                if(isset($payload['company_id'])) $filters['company_id'] = $payload['company_id'];
                if(isset($payload['work_unit_id'])) $filters['work_unit_id'] = $payload['work_unit_id'];
                if(isset($payload['role_id'])) $filters['role_id'] = $payload['role_id'];
    
                return $filters;
            };
            
            $likeFilters = function() use ($payload) {
                $filters = array();
                if(isset($payload['keyword'])) $filters['payroll_title'] = $payload['keyword'];
                if(isset($payload['keyword'])) $filters['company_name'] = $payload['keyword'];
                if(isset($payload['keyword'])) $filters['work_unit_name'] = $payload['keyword'];
                if(isset($payload['keyword'])) $filters['role_name'] = $payload['keyword'];
                if(isset($payload['keyword'])) $filters['created_by'] = $payload['keyword'];
                if(isset($payload['keyword'])) $filters['changed_by'] = $payload['keyword'];
                
                return $filters;
            };
            
            $data = $this->generatePayrollRepository->findAllFilteredRecords($filters(), $likeFilters(), $this->excelService->fields);
            
            $this->excelService->setFileName("GeneratePayroll_".date('YmdHis')."_".time().".xlsx");
            $this->excelService->setWorksheetName("List of Generate Payroll Data");
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