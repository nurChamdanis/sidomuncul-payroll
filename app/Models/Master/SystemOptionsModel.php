<?php

namespace App\Models\Master;

use App\Models\Shared\OptionsModel;

class SystemOptionsModel extends OptionsModel
{
    protected $placeholder = 'Pilih System Tipe';
    protected $fieldsSelect = 'system_type,system_value_txt';
    protected $table = 'tb_m_system_payroll';
    protected $fieldsUsed = array('system_type','system_type');
    protected $fieldsSearch = array('system_value_txt');
    protected $showPlaceholder = true;
    protected $formatText = 'lowercase';
    protected $groupBy = 'system_type';
}