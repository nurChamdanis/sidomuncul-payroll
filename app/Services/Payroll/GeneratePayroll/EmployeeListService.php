<?php

namespace App\Services\Payroll\GeneratePayroll;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Services\BaseService;
use App\Helpers\Datatable;
use App\Repositories\Master\Absence\DWSChangeRequestRepository;
use App\Repositories\Master\Absence\DWSScheduleRepository;
use App\Repositories\Payroll\GeneratePayroll\EmployeeListRepository;
use App\Repositories\Payroll\GeneratePayroll\GeneratePayrollRepository;
use DateTime;

class EmployeeListService extends BaseService{
    protected $employeeListRepository;
    protected $generatePayrollRepository;
    protected $dwsScheduleRepository;
    protected $dwsChangeRequestRepository;
    
    public function __construct()
    {
        parent::__construct();
        $this->employeeListRepository = new EmployeeListRepository();
        $this->generatePayrollRepository = new GeneratePayrollRepository();
        $this->dwsScheduleRepository = new DWSScheduleRepository();
        $this->dwsChangeRequestRepository = new DWSChangeRequestRepository();
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
        $this->serviceAction = '[EMPLOYEELIST][INQUIRY]';
        $self = $this;
        
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
            if(isset($payload['keyword'])) $filters['keyword'] = $payload['keyword'];
            
            return $filters;
        };

        /**
         * get data transactions
         */
        $payroll_period = isset($payload['payroll_period']) ? (!empty($payload['payroll_period']) ? $payload['payroll_period'] : '') : '';
        $transactions = $this->generatePayrollRepository->getAllData(std_date($payroll_period,'m/Y','Y-m'));
        
        /**
         * get dws schedule
         */
        $dws = $this->dwsScheduleRepository->getAllData();
        
        /**
         * get dws changerequests
         */
        $absence_period_start = isset($payload['absence_period_start']) ? (!empty($payload['absence_period_start']) ? $payload['absence_period_start'] : '') : '';
        $absence_period_end = isset($payload['absence_period_end']) ? (!empty($payload['absence_period_end']) ? $payload['absence_period_end'] : '') : '';
        $dwsChangeRequests = $this->dwsChangeRequestRepository->getAllData($absence_period_start, $absence_period_end);
        
        $formattedFields = function ($item) use($self, $dws, $dwsChangeRequests, $transactions, $absence_period_start, $absence_period_end, $payroll_period) {
            ['disabled' => $disabled, 'workDay' => $workDay] = $self->filterDisabled($item, $dws, $dwsChangeRequests, $transactions, $absence_period_start, $absence_period_end, $payroll_period);

            return [
                "<input type='checkbox' class='employee checkboxtable' workday='{$workDay}' id='employee_list_{$item->employee_id}' value='{$item->employee_id}' onclick='selfCheckedEmployeeList(this.id)' {$disabled}/>",
                isEmpty($item->no_reg),
                isEmpty($item->employee_name),
                isEmpty($item->work_unit_name),
                isEmpty($item->role_name),
                isEmpty($item->position_name),
                $workDay,
                $item->employee_id
            ];
        };

        $order      = $payload['order'];
        $column     = $payload['columns'];
        $id_cols    = $order[0]['column'];

        $orderBy = " ";
        if (isset($column[$id_cols]['name'])) {
            $orderBy .= $column[$id_cols]['name'] . " " . $order[0]['dir'];
        }
        
        // Instance datatable class
        // --------------------------------
        $table = new Datatable($this->employeeListRepository, $payload);
        $table->setFilters($filters);
        $table->setFiltersLike($likeFilters);
        $table->setOrderBy($orderBy);

        return $this->dataSuccess( 
            code : 200,
            data : $table->getRows(fn($items) => array_map($formattedFields, $items)),
            message : 'Successfully loaded datatable allowances data.', 
        );
    }

    public function getCheckAllEmployee(?array $payload)
    {
        $self = $this;
        
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
            if(isset($payload['keyword'])) $filters['keyword'] = $payload['keyword'];
            
            return $filters;
        };

        /**
         * get data transactions
         */
        $payroll_period = isset($payload['payroll_period']) ? (!empty($payload['payroll_period']) ? $payload['payroll_period'] : '') : '';
        $transactions = $this->generatePayrollRepository->getAllData(std_date($payroll_period,'m/Y','Y-m'));
        
        /**
         * get dws schedule
         */
        $dws = $this->dwsScheduleRepository->getAllData();
        
        /**
         * get dws changerequests
         */
        $absence_period_start = isset($payload['absence_period_start']) ? (!empty($payload['absence_period_start']) ? $payload['absence_period_start'] : '') : '';
        $absence_period_end = isset($payload['absence_period_end']) ? (!empty($payload['absence_period_end']) ? $payload['absence_period_end'] : '') : '';
        $dwsChangeRequests = $this->dwsChangeRequestRepository->getAllData($absence_period_start, $absence_period_end);
        
        $employees = $this->employeeListRepository->findAllWithLikeFilters($filters(), $likeFilters());
        
        $employee_ids = array_filter(array_map(function($item) use($self, $dws, $dwsChangeRequests, $transactions, $absence_period_start, $absence_period_end, $payroll_period){
            ['disabled' => $disabled, 'workDay' => $workDay] = $self->filterDisabled($item, $dws, $dwsChangeRequests, $transactions, $absence_period_start, $absence_period_end, $payroll_period);

            if($disabled === 'disabled'){
                return ['employee_id' => $item->employee_id, 'workDay' => $workDay, 'disabled' => true];
            } else {
                return ['employee_id' => $item->employee_id, 'workDay' => $workDay, 'disabled' => false];
            }
        }, $employees), function($item){
            return $item['disabled'] === false;
        });

        $getEmployee = array_values(array_map(function($getEmployeeItem) {
            return array('employee_id'=> $getEmployeeItem['employee_id'], 'workDay' => $getEmployeeItem['workDay']);
        }, $employee_ids));
        
        return $this->dataSuccess( 
            code : 200,
            data : !empty($getEmployee) ? array('employees' => $getEmployee, 'employees_total' => count($getEmployee)) :  array(), 
            message : 'Successfully loaded datatable allowances data.', 
        );
    }

    private function filterDisabled($item, $dws, $dwsChangeRequests, $transactions, $absence_period_start, $absence_period_end, $payroll_period){
        $enabled = false;

        $notValid = 0;
        if(!empty($transactions)){
            $getTransactions = array_values(array_filter($transactions, function($transactionItem) use ($payroll_period, $item){
                return std_date($transactionItem->payroll_period, 'Y-m', 'Y-m') == std_date($payroll_period, 'm/Y', 'Y-m') && $transactionItem->employee_id == $item->employee_id;
            }));

            if(isset($getTransactions[0])){
                if(!empty($getTransactions[0])){
                    $notValid++;
                }
            }
        }

        /**
         * Get PWS data & Total Work Day
         */
        $workDay = 0;
        $offDay = 0;
        if(!empty($absence_period_start) && !empty($absence_period_end)){    
            $start_date = new DateTime(std_date($absence_period_start));
            $end_date = new DateTime(std_date($absence_period_end));
            $current_date = clone $start_date;

            $i = 0;

            while ($current_date <= $end_date) {
                $currentDate = strtolower(std_date($current_date->format('Y-m-d'), 'Y-m-d', 'D'));
                $keyDay = $currentDate;
                $dwsId = isset($item->$keyDay) ? $item->$keyDay : '';
                
                $getDwsChangeRequest = array_values(array_filter($dwsChangeRequests, function($dwsCrItem) use ($currentDate){
                    return $dwsCrItem->request_date == $currentDate;
                }));

                if(isset($getDwsChangeRequest[0])){
                    if(!empty($getDwsChangeRequest[0])){
                        $dwsBefore = $getDwsChangeRequest[0]->dws_schedule_before;
                        $dwsAfter = $getDwsChangeRequest[0]->dws_schedule_after;

                        if($dwsBefore == $dwsId){
                            if($dwsId != $dwsAfter){
                                $dwsId = $dwsAfter;
                            }
                        }
                    }
                } 
                
                $getDws = array_values(array_filter($dws, function($dwsItem) use($dwsId){
                    return $dwsItem->dws_id == $dwsId;
                }));

                if(isset($getDws[0])){
                    if(!empty($getDws[0])){
                        if(strtolower($getDws[0]->dws_code) == 'off'){
                            $offDay++;
                        } else {
                            $workDay++;
                        }
                        $i++;
                    }
                }

                $current_date->modify('+1 day');
            }

            if($notValid <= 0){
                $enabled = true;
            }
        }

        $disabled = ($enabled === true) ? '' : 'disabled';

        return array( 'disabled' => $disabled, 'workDay' => $workDay);
    }
}