<?php

namespace App\Repositories\Master\Area;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\BaseRepository;

class AreaGrupRepository extends BaseRepository{
    protected $table = 'tb_m_system_payroll';

    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * @var array $filters
     * @return array $data
     * ----------------------------------------------------
     * name: findAll(filters)
     * desc: Retrieving all data with customized parameters
     */
    public function findAll(array $filters = array())
    {
        $query = $this->db->table($this->table);
        $query->select('system_code,system_value_txt');

        if(!empty($filters)) {
            foreach ($filters as $key => $value) {
                $query->where($key, $this->db->escapeString($value));
            }
        }

        $query->where('system_type','area_group');

        return $query->get()->getResult();
    }
}