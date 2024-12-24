<?php

namespace App\Models\Shared;

use App\Models\Shared\OptionsModel;

class OptionsAreaModel extends OptionsModel
{
    protected $placeholder = 'Pilih Area';
    protected $fieldsSelect = 'work_unit_id,name';
    protected $table = 'tb_m_work_unit';
    protected $fieldsUsed = array('work_unit_id','name');
    protected $fieldsSearch = array('name');
    protected $showPlaceholder = true;
    protected $dependOn = array('company_id');

    public function where($query)
    {
        return $query->where(access_data(fields: 'work_unit_id', type: 'where_in'));
    }
}