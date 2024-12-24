<?php

namespace App\API\Repositories;

use App\API\Models\UserModel;
use App\Repositories\BaseRepository;

class AuthenticationRepository extends BaseRepository{
    protected $userModel;

    public function __construct(){
        $this->userModel = new UserModel();
    }

    /**
     * Find User By Username & Type
     * @var string $username
     * @var string $type
     * @return object User
     */
    public function findUser($username, $type)
    {
        return $this->userModel
                ->where('username', $username)
                ->where('credentials_type', $type)
                ->first();
    }
    
    /**
     * Find User By Username & Type
     * @var string $username
     * @var string $type
     * @return object User
     */
    public function findUserWithToken($username, $type, $token)
    {
        return $this->userModel
                ->where('username', $username)
                ->where('credentials_type', $type)
                ->where('token', $token)
                ->first();
    }

    /**
     * Save Token To Database
     * @var string $username
     * @var string $expired_token
     * @var array $users
     * @return boolean true if successful, false otherwise
     */
    public function saveToken($token, $expired_token, $users = array())
    {
        return $this->userModel->where([
            'username' => $users['username'],
            'credentials_type' => $users['type']
        ])->set(array(
            'token' => $token,
            'expired_token' => $expired_token
        ))->update();
    }
    
    /**
     * Check Token Existence
     * @var string $username
     * @var string $type
     * @var string $token
     * @return countable User
     */
    public function checkToken($username, $type, $token)
    {
        return $this->userModel
                ->where('username', $username)
                ->where('credentials_type', $type)
                ->where('token', $token)
                ->countAllResults();
    }
}