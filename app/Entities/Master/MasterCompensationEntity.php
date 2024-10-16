<?php

namespace App\Entities\Master;

use CodeIgniter\Entity\Entity;

class MasterCompensationEntity extends Entity{
    protected $company_id;
    protected $work_unit_id;
    protected $employee_id;
    protected $compensation_type;
    protected $period;
    protected $total_compensation;
    protected $compensation_description;
    protected $created_by;
    protected $created_dt;
    protected $changed_by;
    protected $changed_dt;

}