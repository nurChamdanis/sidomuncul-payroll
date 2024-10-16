<?php

namespace App\Services\Master\Allowances;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\Master\Allowances\AllowanceAreaRepository;
use App\Repositories\Master\Area\AreaGrupRepository;
use App\Repositories\Master\Area\AreaRepository;
use App\Services\BaseService;

class AllowanceServiceArea extends BaseService{
    protected mixed $repository;
    protected mixed $repositoryAllowanceArea;

    public function __construct()
    {
        $this->repository = new AreaRepository();
        $this->repositoryAllowanceArea = new AllowanceAreaRepository();
    }

    public function getAll($payload = array())
    {
        $this->serviceAction = '[AREA][GET_ALL]';
        
        $params = array();
        if(isset($payload['company_id'])){
            if(!empty($payload['company_id']))
                $params = array('company_id' => $payload['company_id']);
        }

        return $this->dataSuccess( 
            log : true,
            code : 200,
            data : $this->repository->findAll($params),
            message : 'Successfully Get Area Service', 
        );
    }

    public function getByKey(string $allowance_id){
        $this->serviceAction = '[AREA][GET_BY_KEY]';

        return $this->dataSuccess( 
            log : true,
            code : 200,
            data : $this->repositoryAllowanceArea->findAll(array('allowance_id' => $allowance_id, 'area_type' => '0')),
            message : 'Successfully Get Area Data', 
        );
    }
}