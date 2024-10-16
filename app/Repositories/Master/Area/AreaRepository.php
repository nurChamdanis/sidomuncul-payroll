<?php

namespace App\Repositories\Master\Area;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\BaseRepository;

class AreaRepository extends BaseRepository{
    protected $table = 'tb_m_work_unit';

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
        $areaQuery = "
            (
                SELECT {$this->table}.*, tb_m_company.company_name FROM {$this->table} 
                JOIN tb_m_company ON tb_m_company.company_id = {$this->table}.company_id
            ) work_unit
        ";
        $query = $this->db->table($areaQuery);
        $query->select('work_unit_id,code,company_id,name,company_name');

        if(!empty($filters)) {
            foreach ($filters as $key => $value) {
                $query->where($key, $this->db->escapeString($value));
            }
        }

        $query->where(access_data(fields: 'company_id,work_unit_id', type: 'where_in'));

        return $query->get()->getResult();
    }
}