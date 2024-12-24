<?php

namespace App\Services\Master\Loan;

/**
 * @author luthfi.Hakim@arkamaya.co.id
 * @since May 2024
 */

use App\Helpers\Datatable;
use App\Repositories\Master\Loan\LoanRepository;
use App\Services\BaseService;
use App\Services\Logs\PayrollLogService;
use App\Services\Master\Loan\Excel\LoanInquiryService;
use App\Repositories\Master\CompanyRepository;
use App\Repositories\Master\MasterSystemRepository;
use App\Repositories\Master\EmployeeRepository; 

class LoanService extends BaseService{
    protected mixed $loanRepository;
    protected mixed $companyRepository;
    protected mixed $systemRepository;
    protected mixed $employeeRepository;
    protected $excelService;
    protected $payrollLogService;
    protected $functionId = 309;

    public function __construct()
    {
        parent::__construct();
        $this->loanRepository = new LoanRepository();
        $this->payrollLogService = new PayrollLogService();
        $this->excelService = new LoanInquiryService();
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
        $this->serviceAction = '[MASTER_LOAN][INQUIRY]';

        // Configure datatable settings
        // --------------------------------
        $filters = function() use ($payload) {
            $filters = array();
            if(isset($payload['company_id'])) $filters['company_id'] = $payload['company_id'];
            if(isset($payload['work_unit_id'])) $filters['work_unit_id'] = $payload['work_unit_id'];
            if(isset($payload['role_id'])) $filters['role_id'] = $payload['role_id'];
            if(isset($payload['employee_id'])) $filters['employee_id'] = $payload['employee_id'];
            if(isset($payload['cost_center_id'])) $filters['cost_center_id'] = $payload['cost_center_id'];
            if(isset($payload['period_from'])) $filters['period_from'] = $payload['period_from'];
            if(isset($payload['period_to'])) $filters['period_to'] = $payload['period_to'];
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
                    <input type='checkbox' class='loan' id='loan_{$item->loan_id}' value='{$item->loan_id}' onclick=\"selfChecked('checkAll', 'btn_edit_inquiry', 'btn_delete_inquiry', 'loan')\"/>
                    <label for='loan_{$item->loan_id}'></label>
                </div>
                ",
                isEmpty($item->company_name),
                isEmpty($item->work_unit_name),
                isEmpty($item->role_name),
                isEmpty($item->cost_center_desc),
                isEmpty($item->no_reg),
                isEmpty($item->employee_name),
                isEmpty($item->loan_type_name),
                isEmpty($item->loan_duration_name),
                isEmpty(number_format((float)$this->decrypt($item->loan_total), 0, '.', '.')),
                isEmpty($item->deduction_period_start),
                isEmpty($item->deduction_period_end), 
                isEmpty($item->created_by),
                labelDate(isEmpty($item->created_dt)),
                isEmpty($item->changed_by),
                labelDate(isEmpty($item->changed_dt)),
                isEmpty($item->loan_id),
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
        $table = new Datatable($this->loanRepository, $payload);
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
        $this->serviceAction = '[MASTER_LOAN][DELETE]';

        $repository = $this;
        $error      = null;

        $loan_data = json_encode($this->getByKey($body['loan_id'])['data']);
        
        $result     = queryTransaction(function() use ($body, $repository,) {
            $loan = $repository->loanRepository->delete(array('loan_id' => $body['loan_id']));
            return $loan;
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
            'refference_id' => (string) $body['loan_id'],
            'data_before' => $loan_data,
            'data_after' => $loan_data,
            'history_details' => $this->S_NO_REG . ' has deleted data.',
        ));

        return $this->dataSuccess( 
            log : true,
            code : 204,
            data : array('redirect_link' => 'master_loan'), 
            message : 'Successfully Deleted Loan', 
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
        $this->serviceAction = '[MASTER_LOAN][DELETE_SELECTED]';

        $repository = $this;
        $error      = null;
        
        $result     = queryTransaction(function() use ($body, $repository,) {
        $ids = explode(",", $body['ids']);

            if(!empty($ids)){
                foreach ($ids as $value) {
                    $data = json_encode($this->getByKey($value)['data']);
                    $loan = $repository->loanRepository->delete(array('loan_id' => $value));
                    if($loan === true){
                        $this->payrollLogService->create(array(
                            'function_id' => $this->functionId,
                            'refference_id' => (string) $value,
                            'data_before' => $data,
                            'data_after' => $data,
                            'history_details' => $this->S_NO_REG . ' has deleted data.',
                        ));
                    }
                }
            }

            return $loan;
        }, $error);

        if ($result === false) {
            return $this->dataError( 
                        log : true,
                        code : 500,
                        data : null,
                        message : 'Failed Deleted Selected Loan' . ' --> ' . $error,
                    );
        }

        return $this->dataSuccess( 
            log : true,
            code : 204,
            data : array('redirect_link' => 'master_loan'), 
            message : 'Successfully Deleted Selected Loan', 
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
        $this->serviceAction = '[MASTER_LOAN][CREATE]';

        $repository = $this;
        $error = null;
        
        $result = queryTransaction(function() use ($payload,$repository) 
        {
            /**
             * Loan
             */
            $loan = array();
            $loan['company_id'] = $payload['company_id'];
            $loan['work_unit_id'] = $payload['work_unit_id'];
            $loan['employee_id'] = $payload['employee_id'];
            $loan['loan_type'] = $payload['loan_type'];
            $loan['loan_duration'] = $payload['loan_duration'];
            $loan['deduction_period_start'] = $payload['periodFrom'];
            $loan['deduction_period_end'] = $payload['periodTo'];
            $loan['loan_total'] = $this->encrypt(decimalvalue($payload['loan_total']));
            $loan['monthly_deduction'] = $this->encrypt(decimalvalue($payload['monthly_deduction']));
            $loan['loan_paid_off'] = "0";
            $loan['remaining_loan'] = $this->encrypt(decimalvalue($payload['loan_total']));
            $loan['loan_description'] = $payload['remark'];
            $loan['created_by'] = $this->S_NO_REG;
            $loan['created_dt'] = date('Y-m-d H:i:s');
            $loanResult = $repository->loanRepository->save($loan);
            return $loanResult;

        }, 
        $error);

        if ($result === false) {
            return $this->dataError( 
                log : true,
                code : 500,
                data : null,
                message : 'Failed Created Master Loan --> ' . $error,
            );
        }

        $this->payrollLogService->create(array(
            'function_id' => $this->functionId,
            'refference_id' => (string) $result['id'],
            'data_before' => '',
            'data_after' => '',
            'history_details' => $this->S_NO_REG . ' has created new data.',
        ));
        
        return $this->dataSuccess( 
            log : true,
            code : 201,
            data : array ('redirect_link'=>'master_loan/id/'.$result['id']),
            message : 'Successfully Created Master Loan', 
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
        
        $this->serviceAction = '[MASTER_ALLOWANCE][UPDATE]';

        $repository = $this;
        $error = null;
        $loanId = $payload['loan_id'];
        
        // data before
        $dataBefore = json_encode($this->getByKey($loanId)['data']);
        $remaining_loan_before = $this->getByKey($loanId)['data']->remaining_loan;
        $loan_total_before = $this->getByKey($loanId)['data']->loan_total;
        $loan_total_payload  =  decimalvalue($payload['loan_total']);
        
        $payload['remaining_loan'] = ($loan_total_payload > $loan_total_before)
        ? $remaining_loan_before + ($loan_total_payload - $loan_total_before)
        : $loan_total_payload - ($loan_total_before - $remaining_loan_before);

        $result = queryTransaction(function() use ($payload,$repository,&$loanId) 
        {

            // loan update data
            $loan = array();
            $loan['company_id'] = $payload['company_id'];
            $loan['work_unit_id'] = $payload['work_unit_id'];
            $loan['employee_id'] = $payload['employee_id'];
            $loan['loan_type'] = $payload['loan_type'];
            $loan['loan_duration'] = $payload['loan_duration'];
            $loan['deduction_period_start'] = $payload['periodFrom'];
            $loan['deduction_period_end'] = $payload['periodTo'];
            $loan['loan_total'] = $this->encrypt(decimalvalue($payload['loan_total']));
            $loan['monthly_deduction'] = $this->encrypt(decimalvalue($payload['monthly_deduction']));
            $loan['remaining_loan'] = $this->encrypt(decimalvalue($payload['remaining_loan']));
            $loan['loan_paid_off'] = "0";
            $loan['loan_description'] = $payload['remark'];
            $loan['changed_by'] = $this->S_NO_REG;
            $loan['changed_dt'] = date('Y-m-d H:i:s');

            $loanResult = $repository->loanRepository->update($loan, array('loan_id' => $loanId));

            return $loanResult;
        }, 
        $error);

        if ($result === false) {
            return $this->dataError( 
                log : true,
                code : 500,
                data : null,
                message : 'Failed Updated Master Loan --> ' . $error,
            );
        }

        // data after
        $dataAfter = json_encode($this->getByKey($loanId)['data']);

        $this->payrollLogService->create(array(
            'function_id' => $this->functionId,
            'refference_id' => $loanId,
            'data_before' => $dataBefore,
            'data_after' => $dataAfter,
            'history_details' => '',
        ));
        
        return $this->dataSuccess( 
            log : true,
            code : 201,
            data : array ('redirect_link'=>'master_loan/id/'.$loanId),
            message : 'Successfully Updated Master Loan', 
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
        $this->serviceAction = '[MASTER_LOAN][GET_BY_KEY]';

        $data = $this->loanRepository->findByOtherKey(array('loan_id' => $id));
        $data->loan_total = $this->decrypt($data->loan_total);
        $data->monthly_deduction = $this->decrypt($data->monthly_deduction);
        $data->remaining_loan = $this->decrypt($data->remaining_loan);

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
            if(isset($payload['cost_center_id'])) $filters['cost_center_id'] = $payload['cost_center_id'];
            if(isset($payload['period_from'])) $filters['period_from'] = $payload['period_from'];
            if(isset($payload['period_to'])) $filters['period_to'] = $payload['period_to'];
    
            $likeFilters = array();
            if(isset($payload['keyword'])) $filters['employee_name'] = $payload['keyword'];
            
            $data = $this->loanRepository->findAllFilteredRecords($filters, $likeFilters, $this->excelService->fields);
            
            $this->excelService->setFileName("MasterLoan_".date('YmdHis')."_".time().".xlsx");
            $this->excelService->setWorksheetName("List of Master Data Loan");
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