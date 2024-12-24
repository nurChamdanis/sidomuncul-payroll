<?php

namespace App\Services;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Libraries\EncryptionLib;
use CodeIgniter\Entity\Entity;
use Michalsn\Uuid\Config\Uuid as ConfigUuid;
use Michalsn\Uuid\Uuid;

class BaseService{
    protected mixed $repository;
    protected string $secretKey;

    protected string $serviceAction = '';
    protected string $S_EMPLOYEE_ID = '';
    protected string $S_EMPLOYEE_NAME = '';
    protected string $S_USER_NAME = '';
    protected string $S_NO_REG = '';
    protected string $S_USER_GROUP_ID = '';
    
    protected array $response_data = array();
    protected array $datatable_fields = array();

    protected Entity $entity;
    protected $session;
    protected $encryption;
    protected $uuid;

    protected $createdMessage = 'has created new data';
    protected $deletedMessage = 'has deleted data';

    public function __construct()
    {
        $this->session = session();
        $this->uuid = new Uuid(new ConfigUuid());
        $this->S_EMPLOYEE_ID = $this->session->get(S_EMPLOYEE_ID);
        $this->S_EMPLOYEE_NAME = $this->session->get(S_EMPLOYEE_NAME);
        $this->S_USER_NAME = $this->session->get(S_USER_NAME);
        $this->S_NO_REG = $this->session->get(S_NO_REG);
        $this->S_USER_GROUP_ID = $this->session->get(S_USER_GROUP_ID);

        $this->encryption = new EncryptionLib(HRISSIDO2024);

        if(!empty($this->session->get(SIDOKEY)))
        {
            $this->secretKey = $this->encryption->decryptData($this->session->get(SIDOKEY));
        } 
        else 
        {
            $db = \Config\Database::connect();
            $encriptionKey = $db->table('tb_m_system_payroll')->where('system_type', SIDOKEY)->get()->getRow();
            $encriptionKey = !empty($encriptionKey) ? $encriptionKey->system_value_txt : '';
            $this->secretKey = $$this->encryption->decryptData($encriptionKey);
        }
    }

    /**
     * @return void
     * ----------------------------------------------------
     * name: messageNewData()
     * desc: function for get new message
     */
    public function messageCreated()
    {
        return $this->S_EMPLOYEE_NAME . $this->createdMessage;
    }

    /**
     * @return void
     * ----------------------------------------------------
     * name: deleteData()
     * desc: function for get delete message
     */
    public function messsagDeleted()
    {
        return $this->S_EMPLOYEE_NAME . $this->deletedMessage;
    }

    /**
     * @var string $message
     * @return void
     * ----------------------------------------------------
     * name: logError(message)
     * desc: function for save error log message
     */
    public function logError(string $message) : void
    {
        log_message('error', "[{$this->S_EMPLOYEE_NAME}]{$this->serviceAction}{$message}");
    }

    /**
     * @var string $message
     * @return void
     * ----------------------------------------------------
     * name: logSuccess(message)
     * desc: function for save success log message
     */
    public function logSuccess(string $message) : void
    {
        log_message('info', "[{$this->S_EMPLOYEE_NAME}]{$this->serviceAction}{$message}");
    }

    /**
     * @var mixed $data
     * @var string $message
     * @var string $code
     * @var array $meta
     * @var boolean $log
     * @return array
     * ----------------------------------------------------
     * name: dataError(data, message, code, meta, log)
     * desc: Custom function for return error data
     */
    public function dataError(mixed $data, string $message, int $code, array $meta = array(), bool $log = false) : array
    {
        if($log){
            $this->logError($message);
        }

        return array_merge(array(
            'code' => $code,
            'status' => false,
            'data' => $data,
            'message' => $message
        ), $meta);
    }

    /**
     * @var mixed $data
     * @var string $message
     * @var string $code
     * @var array $meta
     * @var boolean $log
     * @return array
     * ----------------------------------------------------
     * name: dataSuccess(data, message, code, meta, log)
     * desc: Custom function for return success data
     */
    public function dataSuccess(mixed $data, string $message, int $code, array $meta = array(), bool $log = false) : array
    {
        if($log){
            $this->logSuccess($message);
        }

        return array_merge(array(
                'code' => $code,
                'status' => true,
                'data' => $data,
                'message' => $message
        ), $meta);
    }

    /**
     * @var request $params
     * @return array
     * ----------------------------------------------------
     * name: getOptions(params)
     * desc: Service to loaded data options
     */
    public function getOptions(?array $params) : array
    {
        try {
            $options = $this->repository->getOptions($params);
        } catch (\Exception $e) {
            return $this->dataError( 
                        log : true,
                        code : 500,
                        data : array(),
                        message : $e->getMessage(),
                    );
        }

        return $this->dataSuccess( 
            log : true,
            code : 200,
            data : $options, 
            message : 'Successfully Loaded Options Data', 
        );
    }

    /**
     * @var request $data
     * @return array
     * ----------------------------------------------------
     * name: encrypt(data)
     * desc: Service to encrypt data
     */
    public function encrypt($data){
        return $this->encryption->encryptData($data, $this->secretKey);
    }
    
    /**
     * @var request $data
     * @return array
     * ----------------------------------------------------
     * name: decrypt(data)
     * desc: Service to decrypt data
     */
    public function decrypt($data){
        return $this->encryption->decryptData($data, $this->secretKey);
    }
}