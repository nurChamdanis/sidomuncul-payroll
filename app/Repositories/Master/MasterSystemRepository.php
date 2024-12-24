<?php

namespace App\Repositories\Master;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Models\Master\SystemOptionsModel;
use App\Repositories\BaseRepository;
use App\Repositories\Shared\Select2Repository;

class MasterSystemRepository extends BaseRepository{
    protected $table = 'tb_m_system_payroll';
    protected $modelSystemOptions;

    public function __construct()
    {
        parent::__construct();
        $this->modelSystemOptions = new SystemOptionsModel();
    }
    
    /**
     * @var int $page
     * @var string $search
     * @return array $data
     * ----------------------------------------------------
     * name: getOptions(page, search)
     * desc: Retrieving all data for options select
     */
    public function getOptions(array $params) : array
    {
        $page = isset($params['page']) ? $params['page'] : 1;
        $search = isset($params['search']) ? $params['search'] : ''; 
        
        return (new Select2Repository($this->modelSystemOptions))
        ->groupBy('system_type')
        ->getOptions(array(
            'page' => $page,
            'search' => $search
        ));
    }

    /**
     * @var string $sytem_type
     * @var string $system_code
     * @var string $valid_from
     * @return int $data
     * ----------------------------------------------------
     * name: isExists(system_type, system_code, valid_from)
     * desc: Checking data exists or not
     */
    public function isExists(string $system_type, string $system_code, string $valid_from) : int
    {
        $queryBuilder = $this->db->table($this->table);

        $queryBuilder->where([
            'system_type' => $system_type,
            'system_code' => $system_code,
            'valid_from'  => $valid_from
        ]);

        return $queryBuilder->countAllResults();
    }   
}