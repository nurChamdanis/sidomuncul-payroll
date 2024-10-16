<?php

namespace App\Repositories\Master\PKH;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\BaseRepository;
use stdClass;

class PKHProductRepository extends BaseRepository{
    protected $table = 'tb_m_payroll_allowances';
    protected $tb_m_allowances_area = 'tb_m_payroll_allowances_area';
    protected $tb_m_company = 'tb_m_company';
    protected $tb_m_work_unit = 'tb_m_work_unit';
    protected $tb_m_system = 'tb_m_system_payroll';
    protected $tb_m_employee = 'tb_m_employee';
    protected $tb_m_gl_account = 'tb_m_payroll_gl';
    protected $modifyTableAndCondition = true;

    public function __construct()
    {
        parent::__construct();
    }
}