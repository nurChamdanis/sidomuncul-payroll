<?php

namespace App\Services\Master\Allowances;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\Master\Allowances\AllowanceRulesRepository;
use App\Repositories\Master\Area\AreaRulesRepository;
use App\Services\BaseService;

class AllowanceServicePayrollRules extends BaseService{
    protected mixed $repository;
    protected mixed $repositoryAllowancePayrollRules;

    public function __construct()
    {
        $this->repository = new AreaRulesRepository();
        $this->repositoryAllowancePayrollRules = new AllowanceRulesRepository();
    }

    public function getAll($payload = array())
    {
        $this->serviceAction = '[AREAGRUP][GET_ALL]';
        
        $params = array();
        if(isset($payload['company_id'])){
            if(!empty($payload['company_id']))
                $params = array('company_id' => $payload['company_id']);
        }
        
        $data = $this->repository->findAll(array_merge(array('rules_type' => '0'),$params));

        return $this->dataSuccess( 
            log : true,
            code : 200,
            data : $data,
            message : 'Successfully Get Area Grup Service', 
        );
    }

    public function getByKey(string $allowance_id){
        $this->serviceAction = '[AREA][GET_BY_KEY]';

        return $this->dataSuccess( 
            log : true,
            code : 200,
            data : $this->repositoryAllowancePayrollRules->findAll(array('allowance_id' => $allowance_id)),
            message : 'Successfully Get Area Data', 
        );
    }
}