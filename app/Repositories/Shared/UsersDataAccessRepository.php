<?php

namespace App\Repositories\Shared;

use App\Repositories\BaseRepository;
use Exception;

/**
 * @author luthfi.aziz@arkamaya.co.id
 * @since May 2024
 */
class UsersDataAccessRepository extends BaseRepository
{
    public function __construct(){
        parent::__construct();
    }

    public function getAccessData(
        $session_user_name = '',
        $session_employee_id = '',
        $user_group_id = '',
        $delegate_flg = '0'
    )
    {
        $sqlDataAccess = "SELECT
                    usergroup_id,data_type,GROUP_CONCAT( DATAACCESS.data_id SEPARATOR ',' ) AS data_ids 
                FROM
                    tb_m_user_group_payroll_data_access DATAACCESS
                WHERE usergroup_id = ? GROUP BY data_type";
        $executeDataAccess = $this->db->query($sqlDataAccess,array($user_group_id))->getResult();
        if(!$executeDataAccess) throw new Exception('Failed when execute get Employee, Line: 30');

        $whereDataAccess = array();
        if(!empty($dataAccess)){
            foreach ($dataAccess as $value) {
                $whereDataAccess[$value->data_type] = $value->data_ids;
            }
        }

        $sqlWhereEmployee = '';
        if(!empty($whereDataAccess))
        {
            foreach ($whereDataAccess as $key => $value) {
                if($key == "AREA")
                {
                    $sqlWhereEmployee .= " AND FIND_IN_SET(work_unit_id, {$this->db->escape((string) $value)})";
                } 
                
                if($key == "COMPANY")
                {
                    $sqlWhereEmployee .= " AND FIND_IN_SET(company_id, {$this->db->escape((string) $value)})";
                }
                
                if($key == "POSITION")
                {
                    $sqlWhereEmployee .= " AND FIND_IN_SET(position_id, {$this->db->escape((string) $value)})";
                }
                
                if($key == "ROLE")
                {
                    $sqlWhereEmployee .= " AND FIND_IN_SET(role_id, {$this->db->escape((string) $value)})";
                }
            }
        }

        $sqlDeleteEmployeeDataAccess = "DELETE FROM tb_r_user_group_payroll_employee WHERE session_employee_id = ?";
        $executeDeleteEmployeeDataAccess = $this->db->query($sqlDeleteEmployeeDataAccess, array($session_employee_id));
        if(!$executeDeleteEmployeeDataAccess) throw new Exception('Failed when execute get Employee, Line: 66');

        $sqlGetEmployee  = "INSERT INTO tb_r_user_group_payroll_employee (
            session_username, 
            session_employee_id,
            employee_id,
            company_id,
            work_unit_id,
            role_id,
            position_id,
            last_refresh_date,
            delegate_flg,
            created_by,
            created_dt,
            changed_by,
            changed_dt
        ) SELECT 
            '{$session_user_name}',
            '{$session_employee_id}',
            employee_id,
            company_id,
            work_unit_id,
            role_id,
            position_id,
            NOW(),
            '{$delegate_flg}',
            '{$session_user_name}',
            NOW(),
            '{$session_user_name}',
            NOW()
        FROM tb_m_employee WHERE 1=1";

        $sqlGetEmployee .= $sqlWhereEmployee;
        $executeGetEmployee = $this->db->query($sqlGetEmployee);

        if(!$executeGetEmployee) throw new Exception('Failed when execute get Employee, Line: 99');

        return $executeGetEmployee;
    }
}