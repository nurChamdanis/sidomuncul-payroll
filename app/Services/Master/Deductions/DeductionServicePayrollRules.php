<?php

namespace App\Services\Master\Deductions;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\Master\Deductions\DeductionRulesRepository;
use App\Repositories\Master\Area\AreaRulesRepository;
use App\Services\BaseService;

class DeductionServicePayrollRules extends BaseService{
    protected mixed $repository;
    protected mixed $repositoryDeductionPayrollRules;

    public function __construct()
    {
        $this->repository = new AreaRulesRepository();
        $this->repositoryDeductionPayrollRules = new DeductionRulesRepository();
    }

    public function getAll($payload = array())
    {
        $this->serviceAction = '[AREAGRUP][GET_ALL]';
        
        $params = array();
        if(isset($payload['company_id'])){
            if(!empty($payload['company_id']))
                $params = array('company_id' => $payload['company_id']);
        }
        
        $data = $this->repository->findAll(array_merge(array('rules_type' => '1'), $params));

        return $this->dataSuccess( 
            log : true,
            code : 200,
            data : $data,
            message : 'Successfully Get Area Grup Service', 
        );
    }

    public function getByKey(string $deduction_id){
        $this->serviceAction = '[AREA][GET_BY_KEY]';

        return $this->dataSuccess( 
            log : true,
            code : 200,
            data : $this->repositoryDeductionPayrollRules->findAll(array('deduction_id' => $deduction_id)),
            message : 'Successfully Get Area Data', 
        );
    }
}