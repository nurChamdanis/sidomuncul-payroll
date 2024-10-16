<?php

namespace App\Repositories\Options;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Models\Shared\OptionsGLAccountModel;
use App\Repositories\BaseRepository;

class OptionsGLAccountRepository extends BaseRepository{
    protected $model;
    protected $optionsFilter = array('company_id');

    public function __construct()
    {
        parent::__construct();
        $this->model = new OptionsGLAccountModel();
    }
}