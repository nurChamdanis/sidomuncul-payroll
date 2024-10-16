<?php

namespace App\API\Services;

use App\API\Repositories\AuthenticationRepository;
use App\Repositories\Master\MasterSystemRepository;
use DateTime;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthenticationService {
    protected $authenticationRepository;
    protected $masterSystemRepository;

    public function __construct()
    {
        $this->authenticationRepository = new AuthenticationRepository();
        $this->masterSystemRepository = new MasterSystemRepository();
    }

    /**
     * Service for login api
     * @var string $username
     * @var string $password
     * @var string $type
     * @return array
     */
    public function login($username, $password, $type)
    {
        $user = $this->authenticationRepository->findUser($username, $type);

        // Check User Exists
        if (!$user) {
            return array(
                'rc' => 404,
                'status' => false,
                'data'=>null,
                'message' => 'Login failed! User could not be found.'
            );
        }

        // Verified Password
        if (!password_verify($password, $user->user_password)) {
            return array(
                'rc' => 401,
                'status' => false,
                'data'=>null,
                'message' => 'Login failed! Invalid password.'
            );
        }
        
        // Generate Token
        $jwt = $this->generateJWTToken($user);

        // Save Token
        $this->authenticationRepository->saveToken($jwt['token'], $jwt['exd'], array(
            'username' => $username,
            'type' => $type
        ));

        return array(
            'rc' => 200,
            'status' => true,
            'data' => array (
                'credentials_type' => $jwt['typ'],
                'user' => $jwt['sub'],
                'token' => $jwt['token'],
                'expired_date' => $jwt['exd']
            ),
            'message' => 'Authentication successful'
        );
    }

    /**
     * Service generate jwt token
     * @var object $user
     * @return array
     */
    private function generateJWTToken($user)
    {
        $configApi = $this->masterSystemRepository->findAll(array('system_type' => 'config_api'));

        $secretKey = array_values(array_filter($configApi, function($object){return $object->system_code == 'secret_key';}));
        $iss = array_values(array_filter($configApi, function($object){return $object->system_code == 'iss';}));
        $expTime = array_values(array_filter($configApi, function($object){ return $object->system_code == 'expiration_time';}));

        $key = isset($secretKey[0]) ? $secretKey[0]->system_value_txt : SECRET_KEY;
        $issuer = isset($iss[0]) ? $iss[0]->system_value_txt : ISSUER;
        $issuedAt = date('Y-m-d H:i:s');
        $additionalTime = (isset($expTime[0]) ? $expTime[0]->system_value_txt : EXPIRATION_TIME);
        $expirationTime = date('Y-m-d H:i:s', strtotime($issuedAt.$additionalTime)); 
        
        $payload = array(
            'iss'  => $issuer,
            'typ'  => $user->credentials_type,
            'sub'  => $user->username,
            'iat'  => strtotime($issuedAt),
            'exp'  => strtotime($expirationTime),
            'exd'  => $expirationTime
        );

        $jwtToken = JWT::encode($payload, $key, 'HS256');
        return array_merge(array('token' => $jwtToken), $payload);
    }

    /**
     * Service for check validity jwt token
     * @var string $token
     * @return array
     */
    public function checkValidityToken($token)
    {
        $configApi = $this->masterSystemRepository->findAll(array('system_type' => 'config_api'));
        $secretKey = array_values(array_filter($configApi, function($object){return $object->system_code == 'secret_key';}));
        $key = isset($secretKey[0]) ? $secretKey[0]->system_value_txt : SECRET_KEY;
        
        $tokenDecode = JWT::decode($token, new Key((string)$key, 'HS256'));

        $username = $tokenDecode->sub;
        $type = $tokenDecode->typ;
        
        $valid = $this->authenticationRepository->checkToken($username, $type, $token);
        
        if($valid > 0){
            return true;
        }

        return false;
    }

    /**
     * Service for check expiration jwt token
     * @var string $token
     * @return array
     */
    public function checkExpirationToken($token)
    {
        $configApi = $this->masterSystemRepository->findAll(array('system_type' => 'config_api'));
        $secretKey = array_values(array_filter($configApi, function($object){return $object->system_code == 'secret_key';}));
        $key = isset($secretKey[0]) ? $secretKey[0]->system_value_txt : SECRET_KEY;
        
        $tokenDecode = JWT::decode($token, new Key((string)$key, 'HS256'));

        $username = $tokenDecode->sub;
        $type = $tokenDecode->typ;
        
        $user = $this->authenticationRepository->findUserWithToken($username, $type, $token);

        if(empty($user)){
            return false;
        }

        $currentDate = date('Y-m-d H:i:s');

        $date1 = DateTime::createFromFormat('Y-m-d H:i:s', $currentDate);
        $date2 = DateTime::createFromFormat('Y-m-d H:i:s', $user->expired_token);

        if ($date1 > $date2) {
            return false;
        } 

        return true;
    }

    /**
     * Service for login api
     * @var string $username
     * @var string $password
     * @var string $type
     * @return array
     */
    public function logout($token)
    {
        $configApi = $this->masterSystemRepository->findAll(array('system_type' => 'config_api'));
        $secretKey = array_values(array_filter($configApi, function($object){return $object->system_code == 'secret_key';}));
        $key = isset($secretKey[0]) ? $secretKey[0]->system_value_txt : SECRET_KEY;
        
        $decode = JWT::decode($token, new Key((string)$key, 'HS256'));

        $repository = $this->authenticationRepository;
        $message    = 'Successfully logged out.';
        $error      = null;

        $result     = queryTransaction(function() use ($decode, $repository,) {
            return $repository->saveToken(null, null, array(
                'username' => $decode->sub,
                'type' => $decode->typ
            ));
        }, $error);

        if ($result === false) {
            $message = 'Failed logged out.';
            
            log_message('error', "[APIAUTH][LOGOUT][{$decode->sub}] {$message} --> {$error}");
            return array(
                'code'      => 500,
                'status'    => false,
                'data'      => null,
                'message'   => $message . ' --> ' . $error,
            );
        }
        
        log_message('info', "[APIAUTH][LOGOUT][{$decode->sub}] {$message}");
        return array(
            'rc' => 200,
            'status' => true,
            'data' =>  null,
            'message' => 'Successfully logged out.'
        );
    }
}