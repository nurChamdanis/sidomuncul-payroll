<?php

namespace App\API\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'tb_m_payroll_api_credentials'; 
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'user_password', 'token', 'expired_token']; 

    protected $returnType = 'object';

    protected $validationRules = [
        'username' => 'required|min_length[5]|max_length[255]',
        'user_password' => 'required|min_length[8]',
    ];

    // Callbacks to run before/after validation, saving, etc.
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    // Hash the password before saving
    protected function hashPassword(array $data)
    {
        if (!isset($data['data']['password'])) {
            return $data;
        }

        $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_BCRYPT);
        return $data;
    }
}
