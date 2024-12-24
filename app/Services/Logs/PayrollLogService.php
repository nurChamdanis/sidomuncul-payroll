<?php

namespace App\Services\Logs;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Helpers\Datatable;
use App\Repositories\Logs\PayrollLogsRepository;
use App\Services\BaseService;
use Michalsn\Uuid\Config\Uuid as ConfigUuid;
use Michalsn\Uuid\Uuid;

class PayrollLogService extends BaseService{
    protected mixed $payrollLogsRepository;

    public function __construct()
    {
        parent::__construct();
        $this->payrollLogsRepository = new PayrollLogsRepository();
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
        $this->serviceAction = '[PAYROLL_LOG][INQUIRY]';

        // Configure datatable settings
        // --------------------------------
        $filters = function() use ($payload) {
            $filters = array();
            if (isset($payload['function_id'])) $filters['function_id'] = $payload['function_id'];
            if (isset($payload['refference_id'])) $filters['refference_id'] = $payload['refference_id'];

            return $filters;
        };

        $likeFilters = function() use ($payload) {
            $filters = array();
            if (isset($payload['keyword'])) {
                $filters['data_before'] = $payload['keyword'];
                $filters['data_after'] = $payload['keyword'];
                $filters['history_details'] = $payload['keyword'];
            }

            return $filters;
        };

        $formattedFields = function ($item) {
            $differenceHtml = $item->history_details;
            if(!empty($item->data_before) && !empty($item->data_after)){
                $differenceHtml = $this->compareAndGenerateHtml($item->data_before, $item->data_after);
            }
            

            return [
                labelDate(isEmpty($item->created_dt)),
                isEmpty($item->created_by),
                $differenceHtml,
            ];
        };

        $order = $payload['order'];
        $column = $payload['columns'];
        $id_cols = $order[0]['column'];

        $orderBy = " created_dt ASC, ";
        if (isset($column[$id_cols]['name'])) {
            $orderBy .= $column[$id_cols]['name'] . " " . $order[0]['dir'];
        }

        // Instance datatable class
        // --------------------------------
        $table = new Datatable($this->payrollLogsRepository, $payload);
        $table->setFilters($filters);
        $table->setFiltersLike($likeFilters);
        $table->setOrderBy($orderBy);

        return $this->dataSuccess(
            code: 200,
            data: $table->getRows(fn($items) => array_map($formattedFields, $items)),
            message: 'Successfully loaded datatable payroll logs.'
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
        $this->serviceAction = '[PAYROLL_LOGS][CREATE]';

        $repository = $this;
        $error = null;
        
        $result = queryTransaction(function() use ($payload,$repository) 
        {
            /**
             * Allowance
             */
            $payrollLogs = array();
            $payrollLogs['history_payroll_id'] = $this->uuid->uuid4()->toString();
            $payrollLogs['function_id'] = (string) $payload['function_id'];
            $payrollLogs['refference_id'] = $payload['refference_id'];
            $payrollLogs['data_before'] = $payload['data_before'];           
            $payrollLogs['data_after'] = $payload['data_after'];
            $payrollLogs['history_details'] = $payload['history_details'];
            $payrollLogs['created_by'] = $this->S_EMPLOYEE_NAME;
            $payrollLogs['created_dt'] = date('Y-m-d H:i:s');
            $payrollLogsResult = $repository->payrollLogsRepository->save($payrollLogs);

            return $payrollLogsResult;
        }, 
        $error);

        if ($result === false) {
            return $this->dataError( 
                log : true,
                code : 500,
                data : null,
                message : 'Failed Created Master PayrollLogs --> ' . $error,
            );
        }
        
        return $this->dataSuccess( 
            log : true,
            code : 201,
            data : $result,
            message : 'Successfully Created Master PayrollLogs', 
        );
    }

    /**
     * Compare data before and after, and generate HTML for differences.
     *
     * @param string $dataBeforeJson
     * @param string $dataAfterJson
     * @return string
     */
    public function compareAndGenerateHtml(string $dataBeforeJson, string $dataAfterJson): string
    {
        // Decode JSON data
        $dataBefore = json_decode(stripslashes($dataBeforeJson), true);
        $dataAfter = json_decode(stripslashes($dataAfterJson), true);

        // Ensure the decoded JSON is an array
        if (!is_array($dataBefore) && !is_object($dataBefore)) {
            $dataBefore = [];
        }
        if (!is_array($dataAfter) && !is_object($dataAfter)) {
            $dataAfter = [];
        }

        // Compare data_before and data_after
        $differences = [];
        foreach ($dataBefore as $key => $value) {
            if (array_key_exists($key, $dataAfter) && $dataBefore[$key] !== $dataAfter[$key]) {
                // Handle special case for loan_total comparison (if needed)
                if ($key === 'loan_total' && floatval($dataBefore[$key]) !== floatval($dataAfter[$key])) {
                    $differences[$key] = [
                        'before' => $dataBefore[$key],
                        'after' => $dataAfter[$key]
                    ];
                } else {
                    // For other fields, compare as strings
                    if (strval($dataBefore[$key]) !== strval($dataAfter[$key])) {
                        $differences[$key] = [
                            'before' => $dataBefore[$key],
                            'after' => $dataAfter[$key]
                        ];
                    }
                }
            }
        }

        // Generate HTML for differences
        if (!empty($differences)) {
            $differenceHtml = '<ol class="history_payroll_log">';
            foreach ($differences as $key => $difference) {
                $formattedKey = ucwords(str_replace('_', ' ', $key));
                $beforeValue = (ctype_digit($difference['before'])) 
                ? number_format((float)$difference['before'], 0, '.', '.') 
                : $difference['before'];
                $afterValue = (ctype_digit($difference['after'])) 
                ? number_format((float)$difference['after'], 0, '.', '.') 
                : $difference['after'];
                if(empty($difference['before'])){
                    $differenceHtml .= "<li><strong>$formattedKey </strong> changed to <span class='label label-success'> {$afterValue}</span></li>";
                } else {
                    $differenceHtml .= "<li><strong>$formattedKey </strong> changed from <span class='label label-danger'>  {$beforeValue}</span> to <span class='label label-success'> {$afterValue} </span></li>";
                }
                
            }
            $differenceHtml .= '</ol>';
        } else {
            $differenceHtml = '';
        }

        return $differenceHtml;
    }
}