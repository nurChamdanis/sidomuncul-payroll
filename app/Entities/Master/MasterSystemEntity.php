<?php

namespace App\Entities\Master;

use CodeIgniter\Entity\Entity;

class MasterSystemEntity extends Entity
{
    protected $system_type;
    protected $system_code;
    protected $system_code_desc;
    protected $valid_from;
    protected $valid_to;
    protected $system_value_txt;
    protected $system_value_num;
    protected $system_description;
    protected $created_dt;
    protected $created_by;
    protected $changed_dt;
    protected $changed_by;

    
    public function setValidFrom(string $dateString)
    {
        $this->attributes['valid_from'] = std_date($dateString);

        return $this;
    }

    public function setValidTo(string $dateString)
    {
        $this->attributes['valid_to'] = std_date($dateString);

        return $this;
    }
}