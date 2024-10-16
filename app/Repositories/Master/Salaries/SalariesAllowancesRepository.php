<?php

namespace App\Repositories\Master\Salaries;

/**
 * @author luthfi.hakim@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\BaseRepository;
use stdClass;

class SalariesAllowancesRepository extends BaseRepository{
    protected $table = 'tb_m_payroll_basic_salary_allowances';
    public function __construct()
    {
        parent::__construct();
    }
}