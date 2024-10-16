<?php

namespace App\Models\Shared;

use App\Models\Shared\OptionsModel;

class OptionsEmployeeContractModel extends OptionsModel
{
    protected $placeholder = 'Pilih Jenis Kontrak';
    protected $fieldsSelect = 'employee_group_id,employee_group_nm';
    protected $table = 'tb_m_employee_group';
    protected $fieldsUsed = array('employee_group_id','employee_group_nm');
    protected $fieldsSearch = array('employee_group_nm');
    protected $showPlaceholder = true;
}