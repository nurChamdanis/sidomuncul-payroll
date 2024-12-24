<?php

namespace App\Services\Options;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\Options\OptionsCompanyRepository;
use App\Services\BaseService;

class OptionsCompanyService extends BaseService{
    protected mixed $repository;

    public function __construct()
    {
        parent::__construct();
        $this->repository = new OptionsCompanyRepository();
    }
}