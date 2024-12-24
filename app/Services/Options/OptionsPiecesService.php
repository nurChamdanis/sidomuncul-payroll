<?php

namespace App\Services\Options;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\Options\OptionsPiecesRepository;
use App\Services\BaseService;

class OptionsPiecesService extends BaseService{
    protected mixed $repository;

    public function __construct()
    {
        parent::__construct();
        $this->repository = new OptionsPiecesRepository();
    }
}