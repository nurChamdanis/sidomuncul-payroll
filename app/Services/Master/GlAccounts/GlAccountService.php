<?php

namespace App\Services\Master\GlAccounts;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\Master\GlAccounts\GlAccountRepository;
use App\Services\BaseService;

class GlAccountService extends BaseService{
    protected mixed $repository;

    public function __construct()
    {
        $this->repository = new GlAccountRepository();
    }

    /**
     * @var string $id
     * @return array
     * ----------------------------------------------------
     * name: getByKey($id)
     * desc: Service to get Data By Key
     */
    public function getByKey(string $id){
        $this->serviceAction = '[ALLOWANCE][GET_BY_KEY]';

        $data = $this->repository->findByOtherKey(array('gl_id' => $id));

        return $this->dataSuccess( 
            log : true,
            code : 200,
            data : $data,
            message : 'Successfully Get Area Data', 
        );
    }
}