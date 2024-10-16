<?php 

namespace App\Models\Master;

use App\Models\Shared\OptionsModel;

class CompanyOptionsModel extends OptionsModel{
    protected $placeholder = 'Select';
    protected $fieldsSelect = 'company_id, company_name';
    protected $table = 'tb_m_company';
    protected $fieldsUsed = array('company_id', 'company_name');
    protected $fieldsSearch  = array('company_name');
    protected $showPlaceholder = true;
    protected $formatText = 'lowercase';
    protected $groupBy = 'company_id';
}