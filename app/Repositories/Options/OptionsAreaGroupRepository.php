<?php

namespace App\Repositories\Options;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Models\Shared\OptionsGroupModel;
use App\Repositories\BaseRepository;

class OptionsAreaGroupRepository extends BaseRepository{
    protected $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new OptionsGroupModel();
    }
}