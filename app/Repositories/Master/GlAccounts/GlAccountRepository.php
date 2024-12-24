<?php

namespace App\Repositories\Master\GlAccounts;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\BaseRepository;

class GlAccountRepository extends BaseRepository{
    protected $table = 'tb_m_payroll_gl';

    public function __construct()
    {
        parent::__construct();
    }
}