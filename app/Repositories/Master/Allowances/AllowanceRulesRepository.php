<?php

namespace App\Repositories\Master\Allowances;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\BaseRepository;

class AllowanceRulesRepository extends BaseRepository{
    protected $table = 'tb_m_payroll_allowances_rules';

    public function __construct()
    {
        parent::__construct();
    }
}