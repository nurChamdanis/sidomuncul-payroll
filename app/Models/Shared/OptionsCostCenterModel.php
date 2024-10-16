<?php

namespace App\Models\Shared;

use App\Models\Shared\OptionsModel;

class OptionsCostCenterModel extends OptionsModel
{
    protected $placeholder =  'Pilih Cost Center';
    protected $fieldsSelect = 'cost_center_id,CONCAT(cost_center_code, " - ", cost_center_desc) AS cost_center_desc';
    protected $table = 'tb_m_cost_center';
    protected $fieldsUsed = array('cost_center_id','cost_center_desc');
    protected $fieldsSearch = array('cost_center_code', 'cost_center_desc');
    protected $showPlaceholder = true;
    protected $dependOn = array('company_id');
}