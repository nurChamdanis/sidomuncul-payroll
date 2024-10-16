<?php

namespace App\Models\Shared;

use App\Models\Shared\OptionsModel;

class OptionsSystemModel extends OptionsModel
{
    // protected $placeholder = 'Pilih Sytem Type';
    protected $fieldsSelect = 'system_code,system_value_txt';
    protected $table = 'tb_m_system_payroll';
    protected $fieldsUsed = array('system_code','system_value_txt');
    protected $fieldsSearch = array('system_value_txt');
    protected $showPlaceholder = true;
    // protected $formatText = 'lowercase';
    protected $dependOn = array('system_type');
}