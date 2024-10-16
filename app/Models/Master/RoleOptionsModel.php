<?php

namespace App\Models\Master;

use App\Models\Shared\OptionsModel;

class RoleOptionsModel extends OptionsModel
{
    protected $placeholder = 'Select';
    protected $fieldsSelect = 'role_id,role_name';
    protected $table = 'tb_m_role';
    protected $fieldsUsed = array('role_id','role_name');
    protected $fieldsSearch = array('role_name');
    protected $showPlaceholder = true;
    protected $formatText = 'lowercase';
    protected $groupBy = 'role_id';
}