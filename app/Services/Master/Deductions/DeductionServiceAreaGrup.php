<?php

namespace App\Services\Master\Deductions;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\Master\Deductions\DeductionAreaRepository;
use App\Repositories\Master\Area\AreaGrupRepository;
use App\Services\BaseService;

class DeductionServiceAreaGrup extends BaseService{
    protected mixed $repository;
    protected mixed $repositoryDeductionAreaGrup;

    public function __construct()
    {
        $this->repository = new AreaGrupRepository();
        $this->repositoryDeductionAreaGrup = new DeductionAreaRepository();
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
    
    public function getByKey(string $deduction_id){
        $this->serviceAction = '[AREAGRUP][GET_BY_KEY]';
        
        return $this->dataSuccess( 
            log : true,
            code : 200,
            data : $this->repositoryDeductionAreaGrup->findAll(array('deduction_id' => $deduction_id, 'area_type' => '1')),
            message : 'Successfully Get Area Data', 
        );
    }
}