<?php

namespace App\Models\Shared;

use App\Models\Shared\OptionsModel;

class OptionsGroupModel extends OptionsModel
{
    protected $placeholder = 'Pilih Group';
    protected $fieldsSelect = 'system_code,system_value_txt';
    protected $table = 'tb_m_system_payroll';
    protected $fieldsUsed = array('system_code','system_value_txt');
    protected $fieldsSearch = array('system_value_txt');
    protected $showPlaceholder = true;
    protected $formatText = 'capitalize';

    public function where($query)
    {
        return $query->where('system_type', 'area_group');
    }
}