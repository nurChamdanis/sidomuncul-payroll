<?php

namespace App\API\V1;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class APIController extends ResourceController{
    use ResponseTrait;

    // Meta Data
    protected $metaData = array();
    
    // Default Success Response
    protected $defaultSuccessResponse = array(
        'rc'  => 200,
        'status' => true,
        'message' => 'Successfully retrieved data'
    );

    // Default Error Response
    protected $defaultErrorResponse = array(
        'rc'  => 500,
        'status' => false,
        'message' => 'Failed retrieved data'
    );

    /**
     * function for set default format response success
     *
     * @var array $data
     * @var string $message
     * @var int $code
     * 
     */
    public function responseSuccess($data = array(), $message = '', $code = 200){
        $response = array_merge($this->defaultSuccessResponse, array('data' => $data));

        if(!empty($message)){
            $response['message'] = $message;	
        }

        if(!empty($code)){
            $response['rc'] = $code;	
        }

        if(!empty($this->metaData)) {
            $response = array_merge($response, array('meta' => $this->metaData));
        }

        return $this->respond($response, $code, $message);
    }

    /**
     * function for set default format response error
     *
     * @var array $data
     * @var string $message
     * @var int $code
     * 
     */
    public function responseError($data = array(), $message = '', $code = 500){
        $response = $this->defaultErrorResponse;

        if(!empty($data)){
            $response = array_merge($response, array('data' => $data));
        }

        if(!empty($message)){
            $response['message'] = $message;	
        }

        if(!empty($code)){
            $response['rc'] = $code;	
        }

        if(!empty($this->metaData)) {
            $response = array_merge($response, array('meta' => $this->metaData));
        }

        return $this->respond($response, $code, $message);
    }

    /**
     * function for set meta data
     *
     * @var array $meta
     * 
     */
    public function setMetaData($meta = array())
    {
        $this->metaData = $meta;
        return $this;
    }
}