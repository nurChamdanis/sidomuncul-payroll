<?php

namespace App\Services\Options;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\Options\OptionsAreaGroupRepository;
use App\Services\BaseService;

class OptionsAreaGroupService extends BaseService{
    protected mixed $repository;

    public function __construct()
    {
        parent::__construct();
        $this->repository = new OptionsAreaGroupRepository();
    }
}