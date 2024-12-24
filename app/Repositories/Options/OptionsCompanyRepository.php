<?php

namespace App\Repositories\Options;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Models\Shared\OptionsCompanyModel;
use App\Repositories\BaseRepository;

class OptionsCompanyRepository extends BaseRepository{
    protected $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new OptionsCompanyModel();
    }
}