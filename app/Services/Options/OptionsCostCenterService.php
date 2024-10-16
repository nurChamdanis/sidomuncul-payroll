<?php

namespace App\Services\Options;

/**
 * @author lutfi.hakim@arkamaya.co.id
 * @since 2024-05-28 
 * --------------------------------
 * @modified luthfi.hakim@arkamaya.co.id on May 2024
 */


use App\Repositories\Options\OptionsCostCenterRepository;
use App\Services\BaseService;

class OptionsCostCenterService extends BaseService{
    protected mixed $repository;

    public function __construct()
    {
        parent::__construct();
        $this->repository = new OptionsCostCenterRepository();
    }
}