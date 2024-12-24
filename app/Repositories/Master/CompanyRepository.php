<?php

namespace App\Repositories\Master;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\BaseRepository;

class CompanyRepository extends BaseRepository{
    protected $table = 'tb_m_company';

    public function __construct()
    {
        parent::__construct();
    }
    
    public function isExistsCompanyName($companyName = '')
    {
        return $this->findByOtherKey(array('company_name' => $companyName));
    }
}