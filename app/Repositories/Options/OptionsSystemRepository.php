<?php

namespace App\Repositories\Options;

/**
 * @author lutfi.hakim@arkamaya.co.id
 * @since 2024-05-28 
 * --------------------------------
 * @modified luthfi.hakim@arkamaya.co.id on May 2024
 */

use App\Models\Shared\OptionsSystemModel;
use App\Repositories\BaseRepository;

class OptionsSystemRepository extends BaseRepository{
    protected $model;
    protected $optionsFilter = array('system_type');

    public function __construct()
    {
        parent::__construct();
        $this->model = new OptionsSystemModel();
    }
}