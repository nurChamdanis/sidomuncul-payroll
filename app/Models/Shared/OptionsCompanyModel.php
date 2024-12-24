<?php

namespace App\Models\Shared;

use App\Models\Shared\OptionsModel;

class OptionsCompanyModel extends OptionsModel
{
    protected $placeholder = 'Pilih Perusahaan';
    protected $fieldsSelect = 'company_id,company_code';
    protected $table = 'tb_m_company';
    protected $fieldsUsed = array('company_id','company_code');
    protected $fieldsSearch = array('company_code');
    protected $showPlaceholder = true;

    public function where($query)
    {
        return $query->where(access_data(fields: 'company_id', type: 'where_in'));
    }
}