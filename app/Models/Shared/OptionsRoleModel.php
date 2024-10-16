<?php

namespace App\Models\Shared;

use App\Models\Shared\OptionsModel;

class OptionsRoleModel extends OptionsModel
{
    protected $placeholder = 'Pilih Org. Unit';
    protected $fieldsSelect = 'role_id,role_name';
    protected $table = 'tb_m_role';
    protected $fieldsUsed = array('role_id','role_name');
    protected $fieldsSearch = array('role_name');
    protected $showPlaceholder = true;
    protected $dependOn = array('company_id');

    public function where($query)
    {
        return $query->where(access_data(fields: 'role_id', type: 'where_in'));
    }

}