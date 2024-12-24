<?php

namespace App\Models\Shared;

use App\Models\Shared\OptionsModel;

class OptionsPiecesModel extends OptionsModel
{
    protected $placeholder = 'Pilih Category';
    protected $fieldsSelect = 'system_type,system_code, system_value_txt';
    protected $table = 'tb_m_system_payroll';
    protected $fieldsUsed = array('system_code', 'system_value_txt');
    protected $fieldsSearch = array('system_code');
    protected $showPlaceholder = true;

    public function where($query)
    {
        return $query->where('system_type', 'product_oum'); 
    }

    

}