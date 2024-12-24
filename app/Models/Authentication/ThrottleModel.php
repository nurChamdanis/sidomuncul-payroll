<?php

namespace App\Models\Authentication;

use CodeIgniter\Model;

class ThrottleModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'tb_r_throttles';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'id',
        'ip',
        'created_at',
        'updated_at',
        'user_email',
        'type'
    ];
    
    protected $useTimestamps = false;
    protected $createdField  = 'created_dt';
    protected $updatedField  = 'updated_dt';
}
