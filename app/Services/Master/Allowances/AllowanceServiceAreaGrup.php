<?php

namespace App\Services\Master\Allowances;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\Master\Allowances\AllowanceAreaRepository;
use App\Repositories\Master\Area\AreaGrupRepository;
use App\Services\BaseService;

class AllowanceServiceAreaGrup extends BaseService{
    protected mixed $repository;
    protected mixed $repositoryAllowanceAreaGrup;

    public function __construct()
    {
        $this->repository = new AreaGrupRepository();
        $this->repositoryAllowanceAreaGrup = new AllowanceAreaRepository();
    }

    public function getAll()
    {
        $this->serviceAction = '[AREAGRUP][GET_ALL]';
        
        return $this->dataSuccess( 
            log : true,
            code : 200,
            data : $this->repository->findAll(),
            message : 'Successfully Get Area Grup Service', 
        );
    }
    
    public function getByKey(string $allowance_id){
        $this->serviceAction = '[AREAGRUP][GET_BY_KEY]';
        
        return $this->dataSuccess( 
            log : true,
            code : 200,
            data : $this->repositoryAllowanceAreaGrup->findAll(array('allowance_id' => $allowance_id, 'area_type' => '1')),
            message : 'Successfully Get Area Data', 
        );
    }
}