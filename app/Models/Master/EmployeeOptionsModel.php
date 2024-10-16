<?php

namespace App\Models\Master;

use App\Models\Shared\OptionsModel;

class EmployeeOptionsModel extends OptionsModel
{
    protected $placeholder = 'Select';
    protected $fieldsSelect = 'employee_id,employee_name';
    protected $table = 'tb_m_employee';
    protected $fieldsUsed = array('employee_id','employee_name');
    protected $fieldsSearch = array('employee_name');
    protected $showPlaceholder = true;
    protected $formatText = 'lowercase';
    protected $groupBy = 'employee_id';
}