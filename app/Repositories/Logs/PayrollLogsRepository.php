<?php

namespace App\Repositories\Logs;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */

use App\Repositories\BaseRepository;

class PayrollLogsRepository extends BaseRepository{
    protected $table = 'tb_h_payroll_log';
    protected $tb_m_function = 'tb_m_function_payroll';
    protected $modifyTableAndCondition = true;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return string $compiledselect
     * ----------------------------------------------------
     * name: newTableAliases()
     * desc: Retrieving subquery for new aliases table
     */
    public function setCustomTable()
    {
        $query = $this->db->table($this->table);
        $query->select("
            {$this->table}.*,
            {$this->tb_m_function}.function_name
        ");
        $query->join($this->tb_m_function, "{$this->tb_m_function}.function_id = {$this->table}.function_id");
        $query->groupStart();
        $query->where("{$this->table}.data_before IS NULL")
            ->orWhere("{$this->table}.data_before", '')
            ->orWhere("{$this->table}.data_after IS NULL")
            ->orWhere("{$this->table}.data_after", '')
            ->orWhere("{$this->table}.data_before IS NOT NULL AND {$this->table}.data_after IS NOT NULL AND {$this->table}.data_before != {$this->table}.data_after");
        $query->groupEnd();
        
        $this->customTable = "({$query->getCompiledSelect()}) payroll_logs";
    }
}