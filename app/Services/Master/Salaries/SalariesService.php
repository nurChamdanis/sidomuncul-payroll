<?php

namespace App\Services\Master\Salaries;

/**
 * @author luthfi.Hakim@arkamaya.co.id
 * @since May 2024
 */

use App\Helpers\Datatable;
use App\Repositories\Master\Salaries\SalariesRepository;
use App\Repositories\Master\Salaries\SalariesAllowancesRepository;
use App\Repositories\Master\Salaries\SalariesDeductionsRepository;
use App\Services\BaseService;
use App\Services\Logs\PayrollLogService;
use App\Services\Master\Salaries\Excel\SalariesInquiryService;
use App\Repositories\Master\CompanyRepository;
use App\Repositories\Master\MasterSystemRepository;
use App\Repositories\Master\EmployeeRepository; 
use stdClass;

class SalariesService extends BaseService{
    protected mixed $salariesRepository;
    protected mixed $salariesAllowancesRepository;
    protected mixed $salariesDeductionsRepository;
    protected mixed $companyRepository;
    protected mixed $systemRepository;
    protected mixed $employeeRepository;
    protected $excelService;
    protected $payrollLogService;
    protected $functionId = 301;

    public function __construct()
    {
        parent::__construct();
        $this->salariesRepository = new SalariesRepository();
        $this->salariesAllowancesRepository = new SalariesAllowancesRepository();
        $this->salariesDeductionsRepository = new SalariesDeductionsRepository();
        $this->payrollLogService = new PayrollLogService();
        $this->excelService = new SalariesInquiryService();
        $this->companyRepository = new CompanyRepository();
        $this->systemRepository = new MasterSystemRepository();
        $this->employeeRepository = new EmployeeRepository();
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
        $this->serviceAction = '[MASTER_SALARIES][INQUIRY]';

        // Configure datatable settings
        // --------------------------------
        $filters = function() use ($payload) {
            $filters = array();
            if(isset($payload['company_id'])) $filters['company_id'] = $payload['company_id'];
            if(isset($payload['work_unit_id'])) $filters['work_unit_id'] = $payload['work_unit_id'];
            if(isset($payload['role_id'])) $filters['role_id'] = $payload['role_id'];
            if(isset($payload['employee_id'])) $filters['employee_id'] = $payload['employee_id'];
            if(isset($payload['history_flg'])) $filters['history_flg'] = $payload['history_flg'];
            if(isset($payload['period_from'])) $filters['period_from'] = $payload['period_from'];
            if(isset($payload['period_to'])) $filters['period_to'] = $payload['period_to'];
            if(isset($payload['employee_group_id'])) $filters['employee_group_id'] = $payload['employee_group_id'];
            return $filters;
        };

        $likeFilters = function() use ($payload) {
            $filters = array();
            if(isset($payload['keyword'])) $filters['employee_name'] = $payload['keyword'];
            
            return $filters;
        };
                
        $formattedFields = function ($item) {
            return [
                "
                <div class='checkbox checkbox-custom'>
                    <input type='checkbox' class='salary' id='salary_{$item->basic_salary_id}' value='{$item->basic_salary_id}' onclick=\"selfChecked('checkAll', 'btn_edit_inquiry', 'btn_delete_inquiry', 'salary')\"/>
                    <label for='salary_{$item->basic_salary_id}'></label>
                </div>
                ",
                isEmpty($item->company_name),
                isEmpty($item->work_unit_name),
                isEmpty($item->role_name),
                isEmpty($item->no_reg),
                isEmpty($item->employee_name),
                number($this->decrypt($item->basic_salary)),
                number($this->decrypt($item->total_deduction_no_tax)),
                number($this->decrypt($item->total_allowance_no_tax)),
                number($this->decrypt($item->thp_estimation)),
                isEmpty($item->effective_date_start),
                isEmpty($item->created_by),
                labelDate(isEmpty($item->created_dt)),
                isEmpty($item->changed_by),
                labelDate(isEmpty($item->changed_dt)),
                isEmpty($item->basic_salary_id),
            ];
        };

        $order      = $payload['order'];
        $column     = $payload['columns'];
        $id_cols    = $order[0]['column'];

        $orderBy = "";
        if (isset($column[$id_cols]['name'])) {
            $orderBy .= $column[$id_cols]['name'] . " " . $order[0]['dir'];
        }
        
        // Instance datatable class
        // --------------------------------
        $table = new Datatable($this->salariesRepository, $payload);
        $table->setFilters($filters);
        $table->setFiltersLike($likeFilters);
        $table->setOrderBy($orderBy);

        return $this->dataSuccess( 
            log: true,
            code : 200,
            data : $table->getRows(fn($items) => array_map($formattedFields, $items)),
            message : 'Successfully loaded datatable system data.', 
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
        $this->serviceAction = '[MASTER_SALARIES][DELETE]';

        $repository = $this;
        $error      = null;

        $salary_data = json_encode($this->getByKey($body['basic_salary_id'])['data']);
        
        $result     = queryTransaction(function() use ($body, $repository) {
            $deleteAllowances = $repository->salariesAllowancesRepository->delete(array('basic_salary_id' => $body['basic_salary_id']));
            $deleteDeductions = $repository->salariesDeductionsRepository->delete(array('basic_salary_id' => $body['basic_salary_id']));
            if($deleteAllowances && $deleteDeductions):
                $salary = $repository->salariesRepository->delete(array('basic_salary_id' => $body['basic_salary_id']));
            endif;
            return $salary;
        }, $error);

        if ($result === false) {
            return $this->dataError( 
                        log : true,
                        code : 500,
                        data : null,
                        message : 'Failed Deleted System' . ' --> ' . $error,
                    );
        }

        $this->payrollLogService->create(array(
            'function_id' => $this->functionId,
            'refference_id' => (string) $body['basic_salary_id'],
            'data_before' => $salary_data,
            'data_after' => $salary_data,
            'history_details' => $this->S_EMPLOYEE_NAME . ' has deleted data.',
        ));

        return $this->dataSuccess( 
            log : true,
            code : 204,
            data : array('redirect_link' => 'master_salaries'), 
            message : 'Successfully Deleted Salaries', 
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
        $this->serviceAction = '[MASTER_SALARIES][DELETE_SELECTED]';

        $repository = $this;
        $error      = null;
        
        $result     = queryTransaction(function() use ($body, $repository,) {
        $ids = explode(",", $body['ids']);

            if(!empty($ids)){
                foreach ($ids as $value) {
                    $data = json_encode($this->getByKey($value)['data']);
                    $deleteAllowances = $repository->salariesAllowancesRepository->delete(array('basic_salary_id' => $value));
                    $deleteDeductions = $repository->salariesDeductionsRepository->delete(array('basic_salary_id' => $value));
                    if($deleteAllowances && $deleteDeductions){
                        $salary = $repository->salariesRepository->delete(array('basic_salary_id' => $value));
                        if($salary === true){
                            $this->payrollLogService->create(array(
                                'function_id' => $this->functionId,
                                'refference_id' => (string) $value,
                                'data_before' => $data,
                                'data_after' => $data,
                                'history_details' => $this->S_EMPLOYEE_NAME . ' has deleted data.',
                            ));
                        }
                    }
                }
            }

            return $salary;
        }, $error);

        if ($result === false) {
            return $this->dataError( 
                log : true,
                code : 500,
                data : null,
                message : 'Failed Deleted Selected Salaries' . ' --> ' . $error,
            );
        }

        return $this->dataSuccess( 
            log : true,
            code : 204,
            data : array('redirect_link' => 'MASTER_SALARIES'), 
            message : 'Successfully Deleted Selected Salaries', 
        );
    }

    /**
     * @var array $payload
     * @return array
     * -----------------------------------c-----------------
     * name: create($payload)
     * desc: Service to create new allowance data
     */
    public function create(?array $payload) : array
    {
        
        $this->serviceAction = '[MASTER_SALARIES][CREATE]';

        $repository = $this;
        $error = null;
        
        $allowances = json_decode($payload['allowances'], true);
        $deductions = json_decode($payload['deductions'], true);

        $result = queryTransaction(function() use ($payload,$repository, $allowances, $deductions) 
        {
            /**
             * Insert Basic Salary
             */
            $basic_salary = array();
            $basic_salary['company_id'] = $payload['company_id'];
            $basic_salary['work_unit_id'] = $payload['work_unit_id'];
            $basic_salary['employee_id'] = $payload['employee_id'];
            $basic_salary['effective_date_start'] = $payload['effective_date'];
            $basic_salary['effective_date_bpjs'] = $payload['effective_date_bpjs'];
            $basic_salary['attendance_date_start'] = $payload['attendance_date_start'];
            $basic_salary['attendance_date_end'] = $payload['attendance_date_end'];
            $basic_salary['basic_salary'] = $this->encrypt(decimalvalue($payload['basic_salary']));
            $basic_salary['status_ptkp'] = $payload['ptkp'];
            $basic_salary['calc_pph21_flg'] = $payload['calc_pph21_flg'];
            $basic_salary['calc_grossup_flg'] = $payload['calc_grossup_flg'];
            $basic_salary['employee_category'] = $payload['employee_group'];
            $basic_salary['total_allowance_with_tax'] = $this->encrypt(decimalvalue($payload['total_allowance_with_tax']));
            $basic_salary['total_allowance_no_tax'] = $this->encrypt(decimalvalue($payload['total_allowance_no_tax']));
            $basic_salary['total_deduction_with_tax'] = $this->encrypt(decimalvalue($payload['total_deduction_with_tax']));
            $basic_salary['total_deduction_no_tax'] = $this->encrypt(decimalvalue($payload['total_deduction_no_tax']));
            $basic_salary['thp_estimation'] = $this->encrypt(decimalvalue($payload['THP']));
            $basic_salary['history_flg'] = "0";
            $basic_salary['created_by'] = $this->S_EMPLOYEE_NAME;
            $basic_salary['created_dt'] = date('Y-m-d H:i:s');
            $basic_salaryResult = $repository->salariesRepository->save($basic_salary);

            /**
             * Allowance 
             */
            if(!empty($allowances)):
                $i = 0;
                foreach ($allowances as $allowance) {
                    $allowances[$i]['basic_salary_id'] = $basic_salaryResult['id'];
                    $allowances[$i]['allowances_value'] = $this->encrypt(decimalvalue($allowance['allowances_value']));
                    $allowances[$i]['created_by'] = $this->S_NO_REG;
                    $allowances[$i]['created_dt'] = date('Y-m-d H:i:s');
                    $allowances[$i]['changed_by'] = $this->S_NO_REG;
                    $allowances[$i]['changed_dt'] = date('Y-m-d H:i:s');
                    $i++;
                }
                $repository->salariesAllowancesRepository->insertBatch($allowances);
            endif;
            

            /**
             * Deduction 
             */
            if(!empty($deductions)):
                $i = 0;
                foreach ($deductions as $deduction) {
                    $deductions[$i]['basic_salary_id'] = $basic_salaryResult['id'];
                    $deductions[$i]['deductions_value'] = $this->encrypt(decimalvalue($deduction['deductions_value']));
                    $deductions[$i]['created_by'] = $this->S_NO_REG;
                    $deductions[$i]['created_dt'] = date('Y-m-d H:i:s');
                    $deductions[$i]['changed_by'] = $this->S_NO_REG;
                    $deductions[$i]['changed_dt'] = date('Y-m-d H:i:s');
                    $i++;
                }
                $repository->salariesDeductionsRepository->insertBatch($deductions);
            endif;

            return $basic_salaryResult;
        }, 
        $error);

        if ($result === false) {
            return $this->dataError( 
                log : true,
                code : 500,
                data : null,
                message : 'Failed Created Master Salaries --> ' . $error,
            );
        }

        $this->payrollLogService->create(array(
            'function_id' => $this->functionId,
            'refference_id' => (string) $result['id'],
            'data_before' => '',
            'data_after' => '',
            'history_details' => $this->S_EMPLOYEE_NAME . ' has created new data.',
        ));
        
        return $this->dataSuccess( 
            log : true,
            code : 201,
            data : array ('redirect_link'=>'master_salaries/id/'.$result['id']),
            message : 'Successfully Created Master Salaries', 
        );
    }

    /**
     * @var array $payload
     * @return array
     * -----------------------------------c-----------------
     * name: create($payload)
     * desc: Service to create new allowance data
     */
    public function update(?array $payload) : array
    {
        
        $this->serviceAction = '[MASTER_SALARIES][UPDATE]';

        $repository = $this;
        $error = null;
        $isUpdate = false;
        $basic_salary_id = $payload['basic_salary_id'];
        $data_after = (object) $payload;
        $data_before = $this->getByKey($basic_salary_id)['data'];
        $id_target = $basic_salary_id;

        //compare master basic salary data
        $isUpdate = $isUpdate ||
        ($data_before->total_allowance_with_tax != $data_after->total_allowance_with_tax) ||
        ($data_before->basic_salary != decimalvalue($data_after->basic_salary)) ||
        ($data_before->attendance_date_start != $data_after->attendance_date_start) ||
        ($data_before->attendance_date_end != $data_after->attendance_date_end) ||
        ($data_before->calc_pph21_flg != $data_after->calc_pph21_flg) ||
        ($data_before->calc_grossup_flg != $data_after->calc_grossup_flg) ||
        ($data_before->effective_date_start != $data_after->effective_date) ||
        ($data_before->effective_date_bpjs != $data_after->effective_date_bpjs);

        // compare allowance data before and after
        $allowances_before = $this->salariesRepository->findAllowanceSalaryByOtherKey(array(
            'basic_salary_id' => $basic_salary_id,
        ));
        $allowances_after = json_decode(json_encode(json_decode($payload['allowances'], true)));
        $differencesAllowances = $this->compareArrays($allowances_before, $allowances_after, 'allowances');
        $isUpdate = (!empty($differencesAllowances)) ? true : $isUpdate;

        // compare deductions data before and after
        $deductions_before = $this->salariesRepository->findDeductionSalaryByOtherKey(array(
            'basic_salary_id' => $basic_salary_id,
        ));
        $deductions_after = json_decode(json_encode(json_decode($payload['deductions'], true)));
        $differencesDeductions = $this->compareArrays($deductions_before, $deductions_after, 'deductions');
        $isUpdate = (!empty($differencesDeductions)) ? true : $isUpdate;

        if($isUpdate){

            $allowances = json_decode($payload['allowances'], true);
            $deductions = json_decode($payload['deductions'], true);

            $result = queryTransaction(function() use ($payload,$repository, $allowances, $deductions) 
            {
                /**
                 * Insert Basic Salary
                 */
                $basic_salary = array();
                $basic_salary['company_id'] = $payload['company_id'];
                $basic_salary['work_unit_id'] = $payload['work_unit_id'];
                $basic_salary['employee_id'] = $payload['employee_id'];
                $basic_salary['effective_date_start'] = $payload['effective_date'];
                $basic_salary['effective_date_bpjs'] = $payload['effective_date_bpjs'];
                $basic_salary['attendance_date_start'] = $payload['attendance_date_start'];
                $basic_salary['attendance_date_end'] = $payload['attendance_date_end'];
                $basic_salary['basic_salary'] = $this->encrypt(decimalvalue($payload['basic_salary']));
                $basic_salary['status_ptkp'] = $payload['ptkp'];
                $basic_salary['calc_pph21_flg'] = $payload['calc_pph21_flg'];
                $basic_salary['calc_grossup_flg'] = $payload['calc_grossup_flg'];
                $basic_salary['employee_category'] = $payload['employee_group'];
                $basic_salary['total_allowance_with_tax'] = $this->encrypt(decimalvalue($payload['total_allowance_with_tax']));
                $basic_salary['total_allowance_no_tax'] = $this->encrypt(decimalvalue($payload['total_allowance_no_tax']));
                $basic_salary['total_deduction_with_tax'] = $this->encrypt(decimalvalue($payload['total_deduction_with_tax']));
                $basic_salary['total_deduction_no_tax'] = $this->encrypt(decimalvalue($payload['total_deduction_no_tax']));
                $basic_salary['thp_estimation'] = $this->encrypt(decimalvalue($payload['THP']));
                $basic_salary['history_flg'] = "0";
                $basic_salary['created_by'] = $this->S_EMPLOYEE_NAME;
                $basic_salary['created_dt'] = date('Y-m-d H:i:s');
                $basic_salaryResult = $repository->salariesRepository->save($basic_salary);

                /**
                 * Allowance 
                 */
                if(!empty($allowances)):
                    $i = 0;
                    foreach ($allowances as $allowance) {
                        $allowances[$i]['basic_salary_id'] = $basic_salaryResult['id'];
                        $allowances[$i]['allowances_value'] = $this->encrypt(decimalvalue($allowance['allowances_value']));
                        $allowances[$i]['created_by'] = $this->S_NO_REG;
                        $allowances[$i]['created_dt'] = date('Y-m-d H:i:s');
                        $allowances[$i]['changed_by'] = $this->S_NO_REG;
                        $allowances[$i]['changed_dt'] = date('Y-m-d H:i:s');
                        $i++;
                    }
                    $repository->salariesAllowancesRepository->insertBatch($allowances);
                endif;
                

                /**
                 * Deduction 
                 */
                if(!empty($deductions)):
                    $i = 0;
                    foreach ($deductions as $deduction) {
                        $deductions[$i]['basic_salary_id'] = $basic_salaryResult['id'];
                        $deductions[$i]['deductions_value'] = $this->encrypt(decimalvalue($deduction['deductions_value']));
                        $deductions[$i]['created_by'] = $this->S_NO_REG;
                        $deductions[$i]['created_dt'] = date('Y-m-d H:i:s');
                        $deductions[$i]['changed_by'] = $this->S_NO_REG;
                        $deductions[$i]['changed_dt'] = date('Y-m-d H:i:s');
                        $i++;
                    }
                    $repository->salariesDeductionsRepository->insertBatch($deductions);
                endif;

                return $basic_salaryResult;
            }, 
            $error);
            
            $id_target = $result['id'];

            if ($result === false) {
                return $this->dataError( 
                    log : true,
                    code : 500,
                    data : null,
                    message : 'Failed Update Master Salaries --> ' . $error,
                );
            }
        }

        $this->payrollLogService->create(array(
            'function_id' => $this->functionId,
            'refference_id' => (string) $id_target,
            'data_before' => '',
            'data_after' => '',
            'history_details' => $this->S_EMPLOYEE_NAME . ' has created new data.',
        ));
        
        return $this->dataSuccess( 
            log : true,
            code : 201,
            data : array ('redirect_link'=>'master_salaries/id/'.$id_target),
            message : 'Successfully Updated Master Salaries', 
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
        $this->serviceAction = '[MASTER_SALARIES][GET_BY_KEY]';

        $data = $this->salariesRepository->findByOtherKey(array('basic_salary_id' => $id));
        
        $fieldsToDecrypt = [
            'basic_salary',
            'total_allowance_with_tax',
            'total_allowance_no_tax',
            'total_deduction_with_tax',
            'total_deduction_no_tax',
            'thp_estimation'
        ];
        
        foreach ($fieldsToDecrypt as $field) {
            $data->$field = $this->decrypt($data->$field);
        }

        return $this->dataSuccess( 
            log : true,
            code : 200,
            data : $data,
            message : 'Successfully Get Area Data', 
        );
    }

    /**
     * @var string $id
     * @return array
     * ----------------------------------------------------
     * name: getByKey($id)
     * desc: Service to get Data By Key
     */
    public function getAllowanceDeductionByKey(string $company_id, string $work_unit_id, string $employee_group){
        $this->serviceAction = '[MASTER_SALARIES][GET_BY_KEY]';

        $dataAllowance = $this->salariesRepository->findAllowanceByOtherKey(array(
            'company_id' => $company_id,
            'work_unit_id' => $work_unit_id,
            'employee_group' => $employee_group,
        ));

        if (!empty($dataAllowance) && is_array($dataAllowance)) {
            foreach ($dataAllowance as $item) {
                if (isset($item->default_value)) {
                    $item->default_value = $this->decrypt($item->default_value);
                }
            }
        }

        $dataDeduction = $this->salariesRepository->findDeductionByOtherKey(array(
            'company_id' => $company_id,
            'work_unit_id' => $work_unit_id,
            'employee_group' => $employee_group,
        ));

        if (!empty($dataDeduction) && is_array($dataDeduction)) {
            foreach ($dataDeduction as $item) {
                if (isset($item->default_value)) {
                    $item->default_value = $this->decrypt($item->default_value);
                }
            }
        }
        
        $responseData = [
            'data' => [
                'allowance' => $dataAllowance,
                'deduction' => $dataDeduction,
            ]
        ];

        return $this->dataSuccess( 
            log : true,
            code : 200,
            data : $responseData,
            message : 'Successfully Get Allowance With Deduction Area Data', 
        );
    }


    /**
     * @var string $id
     * @return array
     * ----------------------------------------------------
     * name: getByKey($id)
     * desc: Service to get Data By Key
     */
    public function getAllowanceDeductionSalaryByKey(string $basic_salary_id){
        $this->serviceAction = '[MASTER_SALARIES][GET_BY_KEY]';

        $dataAllowance = $this->salariesRepository->findAllowanceSalaryByOtherKey(array(
            'basic_salary_id' => $basic_salary_id,
        ));

        if (!empty($dataAllowance) && is_array($dataAllowance)) {
            foreach ($dataAllowance as $item) {
                if (isset($item->default_value)) {
                    $item->default_value = $this->decrypt($item->default_value);
                }
            }
        }

        $dataDeduction = $this->salariesRepository->findDeductionSalaryByOtherKey(array(
            'basic_salary_id' => $basic_salary_id,
        ));

        if (!empty($dataDeduction) && is_array($dataDeduction)) {
            foreach ($dataDeduction as $item) {
                if (isset($item->default_value)) {
                    $item->default_value = $this->decrypt($item->default_value);
                }
            }
        }
        
        $responseData = [
            'data' => [
                'allowance' => $dataAllowance,
                'deduction' => $dataDeduction,
            ]
        ];

        return $this->dataSuccess( 
            log : true,
            code : 200,
            data : $responseData,
            message : 'Successfully Get Allowance With Deduction Salary Data', 
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
            if(isset($payload['company_id'])) $filters['company_id'] = $payload['company_id'];
            if(isset($payload['work_unit_id'])) $filters['work_unit_id'] = $payload['work_unit_id'];
            if(isset($payload['role_id'])) $filters['role_id'] = $payload['role_id'];
            if(isset($payload['employee_id'])) $filters['employee_id'] = $payload['employee_id'];
            if(isset($payload['history_flg'])) $filters['history_flg'] = $payload['history_flg'];
            if(isset($payload['period_from'])) $filters['period_from'] = $payload['period_from'];
            if(isset($payload['period_to'])) $filters['period_to'] = $payload['period_to'];
            if(isset($payload['employee_group_id'])) $filters['employee_group_id'] = $payload['employee_group_id'];
    
            $likeFilters = array();
            if(isset($payload['keyword'])) $filters['employee_name'] = $payload['keyword'];
            
            $data = $this->salariesRepository->findAllFilteredRecords($filters, $likeFilters, $this->excelService->fields);
            
            $this->excelService->setFileName("MasterSalaries_".date('YmdHis')."_".time().".xlsx");
            $this->excelService->setWorksheetName("List of Master Data Salaries");
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

    function compareArrays($array_before, $array_after, $whatCompare) {
        $differences = [];

        foreach ($array_before as $index => $before) {
            $after = $array_after[$index] ?? null;
            if ($after) {
                $diff = new stdClass();
                // Compare specific properties
                if ($before->calculation_type !== $after->calculation_type) {
                    $diff->calculation_type = ['before' => $before->calculation_type, 'after' => $after->calculation_type];
                }
                if($whatCompare == 'allowances'){
                    if ($this->decrypt($before->default_value) !== decimalvalue($after->allowances_value)) {
                        $diff->default_value = ['before' => $before->default_value, 'after' => $after->allowances_value];
                    }
                } else {
                    if ($this->decrypt($before->default_value) !== decimalvalue($after->deductions_value)) {
                        $diff->default_value = ['before' => $before->default_value, 'after' => $after->deductions_value];
                    }
                }   
                if ($before->is_active !== $after->is_active) {
                    $diff->is_active = ['before' => $before->is_active, 'after' => $after->is_active];
                }
                if ($before->pph21_flg !== $after->pph21_flg) {
                    $diff->pph21_flg = ['before' => $before->pph21_flg, 'after' => $after->pph21_flg];
                }
    
                if (!empty((array)$diff)) {
                    $differences[$index] = $diff;
                }
            } else {
                $differences[$index] = ['before' => $before, 'after' => null];
            }
        }
    
        foreach ($array_after as $index => $after) {
            if (!isset($array_before[$index])) {
                $differences[$index] = ['before' => null, 'after' => $after];
            }
        }
    
        return $differences;
    }

}