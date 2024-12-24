<?php

namespace App\Repositories\Master\PKH;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\BaseRepository;

class PKHProductAreaRepository extends BaseRepository{
    protected $table = 'tb_m_payroll_allowances_area';

    public function __construct()
    {
        parent::__construct();
    }
}