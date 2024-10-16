<?php

namespace App\Models\Shared;

use App\Models\Shared\OptionsModel;

class OptionsEmployeeModel extends OptionsModel
{
    protected $placeholder =  'Pilih Employee';
    protected $fieldsSelect = 'employee_id,employee_name';
    protected $table = 'tb_m_employee';
    protected $fieldsUsed = array('employee_id','employee_name');
    protected $fieldsSearch = array('employee_name');
    protected $showPlaceholder = true;
    protected $dependOn = array('company_id', 'work_unit_id', 'role_id');
    
    public function where($query)
    {
        return $query->where(access_data(fields: 'company_id,work_unit_id,role_id,employee_id', type: 'where_in'));
    }

}