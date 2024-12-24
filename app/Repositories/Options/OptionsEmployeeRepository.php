<?php

namespace App\Repositories\Options;

/**
 * @author lutfi.hakim@arkamaya.co.id
 * @since 2024-05-28 
 * --------------------------------
 * @modified luthfi.hakim@arkamaya.co.id on May 2024
 */


use App\Models\Shared\OptionsEmployeeModel;
use App\Repositories\BaseRepository;
use App\Repositories\Shared\Select2Repository;

class OptionsEmployeeRepository extends BaseRepository{
    protected $model;
    protected $optionsFilter = array('company_id', 'work_unit_id', 'role_id');

    public function __construct()
    {
        parent::__construct();
        $this->model = new OptionsEmployeeModel();
    }
}