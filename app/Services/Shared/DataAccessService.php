<?php

namespace App\Services\Shared;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\Shared\UsersDataAccessRepository;
use App\Services\BaseService;

class DataAccessService extends BaseService{
    public mixed $repository;

    public function __construct()
    {
        parent::__construct();
        $this->repository = new UsersDataAccessRepository();
    }

    public function getAccessData()
    {
        $this->serviceAction = '[DATAACCESS][CREATE]';

        $repository = $this;
        $error = null;
        
        $result = queryTransaction(function() use($repository)
        {
            return $repository->getAccessData($this->S_NO_REG,$this->S_EMPLOYEE_ID,$this->S_USER_GROUP_ID);
        }, 
        $error);

        if ($result === false) {
            return $this->dataError( 
                log : true,
                code : 500,
                data : null,
                message : $error,
            );
        }
        
        return $this->dataSuccess( 
            log : true,
            code : 201,
            data : '',
            message : 'Successfully Created Data Access', 
        );
    }
}