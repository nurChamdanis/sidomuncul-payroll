<?php

namespace App\Repositories\Payroll\GeneratePayroll;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\BaseRepository;

class GeneratePayrollEmployeeRepository extends BaseRepository{
    protected $table = 'tb_r_payroll_transaction_employee';
}