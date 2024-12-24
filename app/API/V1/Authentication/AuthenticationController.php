<?php

namespace App\API\V1\Authentication;

use App\API\Services\AuthenticationService;
use App\API\V1\APIController;
use CodeIgniter\HTTP\RequestInterface;

class AuthenticationController extends APIController{
    protected $authenticationService;

    public function __construct()
    {
        $this->authenticationService = new AuthenticationService();
    }

    public function login()
    {
        [
            'rc'        => $code,
            'status'    => $status,
            'message'   => $message,
            'data'      => $data,
        ] = $this->authenticationService->login(
            $this->request->getPost('username'),
            $this->request->getPost('password'),
            $this->request->getPost('type')
        );

        if(!$status) return $this->responseError($data, $message, $code);	
        
        return $this->responseSuccess($data, $message, $code);	
    }
    
    public function logout()
    {
        $header = $this->request->getServer('HTTP_AUTHORIZATION');
        $token = explode(' ', $header)[1];

        [
            'rc'        => $code,
            'status'    => $status,
            'message'   => $message,
            'data'      => $data,
        ] = $this->authenticationService->logout($token);

        if(!$status) return $this->responseError($data, $message, $code);	
        
        return $this->responseSuccess($data, $message, $code);	
    }
}