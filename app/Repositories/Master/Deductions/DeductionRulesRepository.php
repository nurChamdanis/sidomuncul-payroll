<?php

namespace App\Repositories\Master\Deductions;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\BaseRepository;

class DeductionRulesRepository extends BaseRepository{
    protected $table = 'tb_m_payroll_deductions_rules';

    public function __construct()
    {
        parent::__construct();
    }
}