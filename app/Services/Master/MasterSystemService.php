<?php

namespace App\Services\Master;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Entities\Master\MasterSystemEntity;
use App\Helpers\Datatable;
use App\Repositories\Master\MasterSystemRepository;
use App\Services\BaseService;

class MasterSystemService extends BaseService{
    protected mixed $repository;

    public function __construct()
    {
        parent::__construct();
        $this->repository = new MasterSystemRepository();
    }

    /**
     * @var array $payload
     * @return array
     * ----------------------------------------------------
     * name : datatable()
     * desc : Service to loaded datatable
     */
    public function datatable(?array $payload) : array
    {
        $this->serviceAction = '[MASTER_SYSTEM][INQUIRY]';
        
        // Configure datatable settings
        // --------------------------------
        $likeFilters = function() use ($payload) {
            $filters = array();
            $search  = $payload['search'];
            if(isset($payload['search']))
            {
                $filters['system_type'] = $search;
                $filters['system_code'] = $search;
                $filters['system_value_txt'] = $search;
            }
            return $filters;
        };
        
        $formattedFields = function ($item) {
            return [
                isEmpty($item->system_type),
                isEmpty($item->system_code),
                isEmpty($item->system_value_txt),
                lower(isEmpty($item->created_by)),
                lower(isEmpty($item->changed_by)),
                labelDate(isEmpty($item->created_dt)),
                labelDate(isEmpty($item->changed_dt))
            ];
        };
        
        // Instance datatable class
        // --------------------------------
        $table = new Datatable($this->repository, $payload);
        $table->setFiltersLike($likeFilters);

        return $this->dataSuccess( 
            code : 200,
            data : $table->getRows(fn($items) => array_map($formattedFields, $items)),
            message : 'Successfully loaded datatable system data.', 
        );
    }

    /**
     * @var array $params
     * @return array
     * ----------------------------------------------------
     * name: create($params)
     * desc: Service to create new system data
     */
    public function create(?array $params) : array
    {
        $this->serviceAction = '[MASTER_SYSTEM][CREATE]';

        $repository = $this->repository;
        $error      = null;
        
        $result     = queryTransaction(function() use ($params, $repository) {
            // Check If Data Exists
            if($repository->isExists($params['system_type'],$params['system_code'], std_date($params['valid_from'])) > 0){
                throw new \Exception('Data already exists in our system, please send another request.');
            }
            
            $keysExclude = ['old_system_type','old_system_code','old_valid_from', 'system_type_free_text'];
            $entity = new MasterSystemEntity(array_exclude($params, $keysExclude));
            $entity->system_type = isset($params['change_to_free_text']) ? $params['system_type_free_text'] : $params['system_type'];
            $entity->created_dt = date('Y-m-d H:i:s');
            $entity->created_by = $this->S_EMPLOYEE_NAME;

            return $repository->save($entity->toArray());
        }, $error);

        if ($result === false) {
            return $this->dataError( 
                log : true,
                code : 500,
                data : null,
                message : 'Failed Created System --> ' . $error,
            );
        }
        
        return $this->dataSuccess( 
            log : true,
            code : 201,
            data : array ('redirect_link'=>'master_system/id/'.$result['system_type'].'/'.$result['system_code']),
            message : 'Successfully Created System', 
        );
    }
    
    /**
     * @var array $params
     * @return array
     * ----------------------------------------------------
     * name: getMasterSystem($system_type, $system_code)
     * desc: Service to loaded system details
     */
    public function getMasterSystem(string $system_type, string $system_code) : array
    {
        try {
            $data = $this->repository->findByOtherKey(array(
                'system_type' => $system_type,
                'system_code' => $system_code
            ));
        } catch (\Exception $e) {
            return $this->dataError( 
                log : true,
                code : 500,
                data : null,
                message : $e->getMessage()
            );
        }
        
        return $this->dataSuccess( 
            log : true,
            code : 200,
            data : $data, 
            message : 'Successfully Loaded System Data', 
        );
    }

    /**
     * @var array $params
     * @return array
     * ----------------------------------------------------
     * name: update($params)
     * desc: Service to update system data
     */
    public function update(?array $params) : array
    {
        $this->serviceAction = '[MASTER_SYSTEM][UPDATE]';

        $repository = $this->repository;
        $error      = null;

        $result     = queryTransaction(function() use ($params, $repository) {
            $key    = array(
                'system_type' => $params['old_system_type'],
                'system_code' => $params['old_system_code'],
                'valid_from'  => std_date($params['valid_from']),
            );

            // Check If Data Exists
            if(
                $params['system_type'] != $params['old_system_type'] || 
                $params['system_code'] != $params['old_system_code'] || 
                std_date($params['valid_from']) != $params['old_valid_from'])
            {
                $isExists = $repository->isExists($params['system_type'],$params['system_code'],std_date($params['valid_from']));
                if($isExists > 0){
                    throw new \Exception('Data already exists in our system, please send another request.');
                }
            }

            $keysExclude = ['old_system_type','old_system_code','old_valid_from', 'system_type_free_text'];
            $entity = new MasterSystemEntity(array_exclude($params, $keysExclude));
            $entity->system_type = isset($params['change_to_free_text']) ? $params['system_type_free_text'] : $params['system_type'];
            $entity->changed_dt  = date('Y-m-d H:i:s');
            $entity->changed_by  = $this->S_EMPLOYEE_NAME;

            return $repository->update($entity->toArray(), $key);
        }, $error);

        if ($result === false) {
            return $this->dataError( 
                        log : true,
                        code : 500,
                        data : null,
                        message : 'Failed Updated System' . ' --> ' . $error,
                    );
        } 
        
        return $this->dataSuccess( 
            log : true,
            code : 201,
            data : array('redirect_link'=>'master_system/id/'.$result['system_type'].'/'.$result['system_code']), 
            message : 'Successfully Updated System', 
        );
    }

    /**
     * @var request $params
     * @return array
     * ----------------------------------------------------
     * name: remove($params)
     * desc: Service to delete system data
     */
    public function remove(?array $params) : array
    {
        $this->serviceAction = '[MASTER_SYSTEM][DELETE]';

        $repository = $this->repository;
        $error      = null;
        
        $result     = queryTransaction(function() use ($params, $repository,) {
            $key        = array(
                'system_type' => $params['system_type'],
                'system_code' => $params['system_code'],
                'valid_from'  => $params['valid_from'],
            );

            return $repository->delete($key);
        }, $error);

        if ($result === false) {
            return $this->dataError( 
                        log : true,
                        code : 500,
                        data : null,
                        message : 'Failed Deleted System' . ' --> ' . $error,
                    );
        }

        return $this->dataSuccess( 
            log : true,
            code : 204,
            data : array('redirect_link' => 'master_system'), 
            message : 'Successfully Deleted System', 
        );
    }

    /**
     * @return array
     * ----------------------------------------------------
     * name: getCalculationType()
     * desc: Service to get all data calculation types
     */
    public function getCalculationType(){
        $this->serviceAction = '[MASTER_SYSTEM][GET_CALCULATION_TYPE]';

        return $this->dataSuccess( 
            log : false,
            code : 200,
            data : $this->repository->findAll(array('system_type' => 'calculation_type')),
            message : 'Successfully Get Calculation Type', 
        );
    }

    /**
     * @return array
     * ----------------------------------------------------
     * name: getCalculationType()
     * desc: Service to get all data calculation modes
     */
    public function getCalculationMode(){
        $this->serviceAction = '[MASTER_SYSTEM][GET_CALCULATION_MODE]';

        return $this->dataSuccess( 
            log : false,
            code : 200,
            data : $this->repository->findAll(array('system_type' => 'calculation_mode')),
            message : 'Successfully Get Calculation Mode', 
        );
    }
    
    /**
     * @return array
     * ----------------------------------------------------
     * name: getCalculationType()
     * desc: Service to get all data calculation types
     */
    public function getPayrollGenerateOptions(){
        $this->serviceAction = '[MASTER_SYSTEM][GET_PAYROLL_GENERATE_OPTIONS]';

        return $this->dataSuccess( 
            log : false,
            code : 200,
            data : $this->repository->findAll(array('system_type' => 'payroll_generate_options')),
            message : 'Successfully Get Payroll Generate Options', 
        );
    }
}