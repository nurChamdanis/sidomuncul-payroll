<?php 

namespace App\Models\Master;

use App\Models\Shared\OptionsModel;

class CompensationTypeOptionsModel extends OptionsModel{
    protected $placeholder = 'Select';
    protected $fieldsSelect = 'system_code, system_value_txt,system_type';
    protected $table = 'tb_m_system_payroll';
    protected $fieldsUsed = array('system_code', 'system_value_txt');
    protected $fieldsSearch  = array('system_value_txt');
    protected $showPlaceholder = true;
    protected $formatText = 'default';

    public function where($query)
    {
        return $query->where("system_type = 'compensation_type'");
    }
}

