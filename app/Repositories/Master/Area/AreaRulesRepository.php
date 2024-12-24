<?php

namespace App\Repositories\Master\Area;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\BaseRepository;

class AreaRulesRepository extends BaseRepository{
    protected $table = 'tb_m_payroll_rules';

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
        $rulesQuery = "
            (
                SELECT {$this->table}.*, tb_m_company.company_name FROM {$this->table} 
                JOIN tb_m_company ON tb_m_company.company_id = {$this->table}.company_id
            ) payroll_rules
        ";
        $query = $this->db->table($rulesQuery);
        $query->select('payroll_rules_id,rules_name,company_name,rules_code');

        if(!empty($filters)) {
            foreach ($filters as $key => $value) {
                $query->where($key, $this->db->escapeString($value));
            }
        }
        
        $query->where(access_data(fields: 'company_id,work_unit_id,role_id', type: 'where_in'));

        return $query->get()->getResultArray();
    }
}