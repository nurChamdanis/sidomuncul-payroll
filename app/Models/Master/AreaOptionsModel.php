<?php

namespace App\Models\Master;

use App\Models\Shared\OptionsModel;

class AreaOptionsModel extends OptionsModel
{
    protected $placeholder = 'Select';
    protected $fieldsSelect = 'work_unit_id,name';
    protected $table = 'tb_m_work_unit';
    protected $fieldsUsed = array('work_unit_id','name');
    protected $fieldsSearch = array('name');
    protected $showPlaceholder = true;
    protected $formatText = 'lowercase';
    protected $groupBy = 'work_unit_id';
}