<?php

namespace App\Repositories\Options;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Models\Shared\OptionsAreaModel;
use App\Repositories\BaseRepository;
use App\Repositories\Shared\Select2Repository;

class OptionsAreaRepository extends BaseRepository{
    protected $model;
    protected $optionsFilter = array('company_id');

    public function __construct()
    {
        parent::__construct();
        $this->model = new OptionsAreaModel();
    }
}