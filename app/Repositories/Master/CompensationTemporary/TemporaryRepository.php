<?php

namespace App\Repositories\Master\CompensationTemporary;

use App\Repositories\BaseRepository;
use Exception;
use stdClass;

class TemporaryRepository extends BaseRepository
{
    protected $table = 'tb_t_payroll_compensation';
    protected $modifyTableAndCondition = true;

    public function isNameExists(string $table, string $columnName, array $data): int
    {
        $queryBuilder = $this->db->table($table);

        $queryBuilder->where([
            $columnName => $data,
        ]);

        return $queryBuilder->countAllResults();
    }

    public function getIdFromName(string $table_name, string $columnName, string $idColumn, string $name): ?string
    {
        $queryBuilder = $this->db->table($table_name);
        $queryBuilder->select($idColumn);
        $queryBuilder->where($columnName, $name);
        $row = $queryBuilder->get()->getRow();

        return $row ? $row->$idColumn : null;
    }


    public function setCustomTable()
    {
        $query = $this->db->table($this->table);
        $query->select("{$this->table}.*, 
        tb_m_company.company_name, 
        tb_m_work_unit.name, 
        tb_m_role.role_name, 
        tb_m_employee.no_reg, 
        tb_m_employee.role_id, 
        tb_m_employee.employee_name, 
        tb_m_system_payroll.system_type, 
        tb_m_system_payroll.system_value_txt");
        $query->join('tb_m_company', "tb_m_company.company_id = {$this->table}.company_id", "left");
        $query->join('tb_m_work_unit', "tb_m_work_unit.work_unit_id = {$this->table}.work_unit_id", "left");
        $query->join('tb_m_employee', "tb_m_employee.employee_id = {$this->table}.employee_id", "left");
        $query->join('tb_m_role', 'tb_m_role.role_id = tb_m_employee.role_id', "left");
        $query->join('tb_m_system_payroll', "tb_m_system_payroll.system_code = {$this->table}.compensation_type AND tb_m_system_payroll.system_type = 'compensation_type'", "left");
        $this->customTable = "({$query->getCompiledSelect()}) temporary_master_compensation";
    }

    public function where($query, $filters)
    {
        $query->where("`system_type` = 'compensation_type'");
        // echo $query->getCompiledSelect();
        // exit();
    }



    public function importExcel($process_id, $company_id)
    {
        $sql = "INSERT INTO tb_r_payroll_compensation (
        company_id, 
        work_unit_id, 
        employee_id, 
        compensation_type, 
        period,
        total_compensation,
        compensation_description,
        process_id,
        created_by, 
        created_dt, 
        changed_by, 
        changed_dt
        )
        SELECT 
            company_id,
            work_unit_id,
            employee_id,
            compensation_type,
            period,
            total_compensation,
            compensation_description,
            CONCAT(process_id,transaction_id) as process_id,
            created_by,
            created_dt,
            changed_by,
            changed_dt
        FROM 
            tb_t_payroll_compensation
        WHERE (1=1)
        AND process_id = '{$process_id}'
        AND valid_flg = '1'
        ";

        $executeInsertInto = $this->db->query($sql . " AND update_flg = '0'");
        // $lastQuery = $this->db->getLastQuery()->getQuery();
        // data_dump($lastQuery);

        if (!$executeInsertInto) throw new Exception('Failed execute importExcel: line 93');

        $updateTableData = $this->db->table($this->table);
        $updateTableData->where(array('update_flg' => '1', 'process_id' => $process_id));
        $updateData = $updateTableData->get()->getResult();
        // data_dump($updateData);

        if (!empty($updateData)) {

            // if empty data, then update table tb_r_payroll_compensation wheren $updateData['employee_id'], $updateData['compensation_type'], $updateData['period']

            foreach ($updateData as $data) {
                unset($data->transaction_id);
                unset($data->valid_flg);
                unset($data->update_flg);
                unset($data->error_message);
                unset($data->created_by);
                // Set 'changed_dt' to the value of 'created_dt'
                $data->changed_dt = $data->created_dt;
                // Optionally, if 'created_dt' should not be updated, unset it
                unset($data->created_dt);

                $this->db->table('tb_r_payroll_compensation')
                    ->where('employee_id', $data->employee_id)
                    ->where('compensation_type', $data->compensation_type)
                    ->where('period', $data->period)
                    ->update((array)$data);
            }


        }
        return true;
    }
}
