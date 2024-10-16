<?php

namespace App\Models\Authentication;

use Exception;

class LoginModel 
{
	protected $db;
    
	function __construct()
	{
		$this->db = \Config\Database::connect();
	}
	
	function auth_user($user_name)
	{
		$sqlAuth = "
			select 
				tb_m_users.user_email as user_name
				, tb_m_users.user_group_id
				, tb_m_users.user_group_payroll_id
				, tb_m_users.access_payroll_app
                , tb_m_users.full_name
                , tb_m_users.user_password
				, tb_m_users.user_email
				, e.photo as photo
				, e.employee_name
				, e.lang_id
				, tb_m_users.company_id
				, tb_m_company.company_name
				, tb_m_company.trial_expired
				, tb_m_user_group.is_admin
				, tb_m_user_group.default_landing
				, case when(tb_m_company.trial_expired <= now()) then 1 else 0 end as is_expired
				, e.employee_id
				, tb_m_position.position_id
				, tb_m_position.position_name
				, e.no_reg
				, (SELECT COALESCE ( GROUP_CONCAT( DISTINCT tb_m_company.company_id ), e.company_id, e2.company_id, '' ) 
					FROM
						tb_m_company
						LEFT JOIN tb_m_user_group_data_access ON tb_m_company.company_id = tb_m_user_group_data_access.data_id AND tb_m_user_group_data_access.data_type = 'COMPANY' 			
						LEFT JOIN tb_m_employee e2 ON tb_m_company.company_id = e2.company_id 
					WHERE
						tb_m_user_group_data_access.usergroup_id = tb_m_users.user_group_id
						OR
						( e2.company_id IS NOT NULL AND tb_m_user_group_data_auth.subordinate_flg = 1 AND ( e2.atasan_id = e.employee_id OR e2.atasan2_id = e.employee_id  OR e2.atasan3_id = e.employee_id) ) 
					) AS access_company_id
				, (SELECT COALESCE(GROUP_CONCAT(DISTINCT tb_m_work_unit.work_unit_id), '') FROM tb_m_work_unit
						left join tb_m_user_group_data_access on tb_m_user_group_data_access.data_type = 'AREA' 	
						left join tb_m_employee e2 on tb_m_work_unit.work_unit_id = e2.work_unit_id
						WHERE 
						(tb_m_work_unit.work_unit_id = tb_m_user_group_data_access.data_id and tb_m_user_group_data_access.usergroup_id = tb_m_users.user_group_id)
						OR 
						(tb_m_work_unit.work_unit_id = e.work_unit_id AND tb_m_user_group_data_auth.related_area_flg = 1)
						OR 
						(
							e2.work_unit_id IS NOT null AND tb_m_user_group_data_auth.subordinate_flg = 1 
							AND (tb_m_user_group_data_auth.related_area_flg = 1 OR tb_m_user_group_data_access.usergroup_id = tb_m_users.user_group_id)
							AND (e2.atasan_id = e.employee_id OR e2.atasan2_id = e.employee_id OR e2.atasan3_id = e.employee_id) 
						)
					) AS access_area_id
				, (SELECT COALESCE(GROUP_CONCAT(DISTINCT tb_m_position.position_id), '') FROM tb_m_position
						left join tb_m_user_group_data_access on tb_m_user_group_data_access.data_type = 'POSITION' 
						left join tb_m_employee e2 on tb_m_position.position_id = e2.position_id
						WHERE 
						(tb_m_position.position_id = tb_m_user_group_data_access.data_id and tb_m_user_group_data_access.usergroup_id = tb_m_users.user_group_id)
						OR 
						(tb_m_position.position_id = e.position_id AND tb_m_user_group_data_auth.related_position_flg = 1)
						OR (
							e2.position_id IS NOT null AND tb_m_user_group_data_auth.subordinate_flg = 1 
							AND (tb_m_user_group_data_auth.related_position_flg = 1 OR tb_m_user_group_data_access.usergroup_id = tb_m_users.user_group_id)
							AND (e2.atasan_id = e.employee_id OR e2.atasan2_id = e.employee_id OR e2.atasan3_id = e.employee_id)
						)
					) AS access_position_id
				, (SELECT COALESCE(GROUP_CONCAT(DISTINCT tb_m_role.role_id), '') FROM tb_m_role
						left join tb_m_user_group_data_access on tb_m_user_group_data_access.data_type = 'ROLE' 
						left join tb_m_employee e2 on tb_m_role.role_id = e2.role_id
						WHERE 
						(tb_m_role.role_id = tb_m_user_group_data_access.data_id and tb_m_user_group_data_access.usergroup_id = tb_m_users.user_group_id)
						OR 
						(tb_m_role.role_id = e.role_id AND tb_m_user_group_data_auth.related_role_flg = 1)
						OR (
							e2.role_id IS NOT null AND tb_m_user_group_data_auth.subordinate_flg = 1 
							AND (tb_m_user_group_data_auth.related_role_flg = 1 OR tb_m_user_group_data_access.usergroup_id = tb_m_users.user_group_id)
							AND (e2.atasan_id = e.employee_id OR e2.atasan2_id = e.employee_id OR e2.atasan3_id = e.employee_id)
						)
					) AS access_role_id
				, (SELECT COALESCE(GROUP_CONCAT(DISTINCT employee_id),'') FROM tb_m_employee WHERE (atasan_id = e.employee_id OR atasan2_id = e.employee_id OR atasan3_id = e.employee_id OR employee_id = e.employee_id) AND tb_m_user_group_data_auth.subordinate_flg = 1 AND tb_m_user_group.is_admin = 0)
				AS access_subordinate_id
				, CONCAT((SELECT COALESCE(GROUP_CONCAT(DISTINCT employee_id),'') FROM tb_m_users_approver_delegation WHERE user_email = e.user_email), ',', e.employee_id)
				AS access_approval_id
				, 'ID' AS lang_code
			from 
				tb_m_users
				inner join tb_m_company on tb_m_company.company_id = tb_m_users.company_id
				inner join tb_m_user_group on tb_m_user_group.user_group_id = tb_m_users.user_group_id
				left join tb_m_employee e on e.employee_id = tb_m_users.employee_id
				left join tb_m_position on tb_m_position.company_id = tb_m_users.company_id and tb_m_position.position_id = e.position_id
				LEFT JOIN tb_m_user_group_data_auth on tb_m_user_group_data_auth.usergroup_id = tb_m_users.user_group_id
				left join tb_m_languages on tb_m_languages.lang_id = e.lang_id
			where 
				e.no_reg = '" . $this->db->escapeString($user_name) . "'
				and tb_m_users.is_active = 1 and tb_m_users.email_confirmed = 1
		";		

		return $this->db->query($sqlAuth)->getResult();	
	}
	
	function last_logged_in($user_email)
	{
		$this->db->query(
		"
			update tb_m_users set user_last_logged_in = now() 
			where user_email = '" . $user_email . "'
		");
	}
        
	function getEmployeeByUname($email)
	{
		$sql = "select user_email, full_name, email_confirmed from tb_m_users where user_email = '" . $this->db->escapeString($email) . "' ";
		$query = $this->db->query($sql);
		if ($query->getNumRows() == 1) {
			return $query->getRow();
		} else {
			return false;
		}
	}
	
	function get_email_by_reset_password_code($k)
	{
		$sql = "select user_email from tb_m_users where reset_password_code = '" . $k . "'";
		$result = $this->db->query($sql);
		if ($result->getNumRows() > 0)
		{
			return $result->getRow()->user_email;
		}
		else
		{
			return false;
		}
	}
	
	function get_email_by_confirmed_password_code($k)
	{
		$sql = "
			select
				tb_m_users.user_email
				, tb_m_employee.no_reg 
			from tb_m_users 
			join tb_m_employee on tb_m_employee.employee_id = tb_m_users.employee_id
			where email_confirmed_code = '" . $k . "'
		";
		$result = $this->db->query($sql);
		if ($result->getNumRows() > 0)
		{
			return $result->getRow();
		}
		else
		{
			return false;
		}
	}
	
	function reset_password($data, $email, $isReset = false, $key ="" )
	{
        $builder = $this->db->table('tb_m_users');
		$builder->where('user_email', $email);
		
		if($isReset == true && $key != ""){
			$builder->where('reset_password_code', $key);
		}
		$builder->update($data);
		return $this->db->affectedRows();
	}        
	function get_approver_delegation ($user_email, $employee_id = "-1")
	{
		$sql = "
			
			SELECT 
				COALESCE(GROUP_CONCAT(DISTINCT e.company_id), '') AS access_company_id
				, COALESCE(GROUP_CONCAT(DISTINCT e.work_unit_id), '') AS access_area_id
				, COALESCE(GROUP_CONCAT(DISTINCT e.position_id), '') AS access_position_id
				, COALESCE(GROUP_CONCAT(DISTINCT e.role_id), '') AS access_role_id
				, COALESCE(GROUP_CONCAT(DISTINCT e.employee_id), '') AS access_subordinate_id
			FROM (
				SELECT e.company_id, e.work_unit_id, e.position_id, e.role_id, e.employee_id
				from tb_m_users_approver_delegation u
				LEFT JOIN tb_m_employee e ON u.employee_id = e.atasan_id OR u.employee_id = e.atasan2_id OR u.employee_id = e.atasan3_id
				WHERE u.user_email = '".$user_email."'

				union

				select e.company_id, e.work_unit_id, e.position_id, e.role_id, e.employee_id
				from tb_m_employee e
				join tb_r_official_travel tr on e.employee_id = tr.employee_id
				join tb_r_official_travel_reviewer trv on trv.travel_id = tr.travel_id
				where trv.reviewer_id = '".$employee_id."' group by e.company_id, e.work_unit_id, e.position_id, e.role_id, e.employee_id

				union 

				select e.company_id, e.work_unit_id, e.position_id, e.role_id, e.employee_id
				from tb_m_employee e
				join tb_r_reimburse tr on e.employee_id = tr.employee_id
				join tb_r_reimburse_reviewer trv on trv.reimburse_id = tr.reimburse_id
				where trv.reviewer_id = '".$employee_id."' group by e.company_id, e.work_unit_id, e.position_id, e.role_id, e.employee_id

				union 

				select e.company_id, e.work_unit_id, e.position_id, e.role_id, e.employee_id
				from tb_m_employee e
				join tb_r_time_off tr on e.employee_id = tr.employee_id
				join tb_r_time_off_reviewer trv on trv.time_off_id = tr.time_off_id
				where trv.reviewer_id = '".$employee_id."' group by e.company_id, e.work_unit_id, e.position_id, e.role_id, e.employee_id

				union 

				select e.company_id, e.work_unit_id, e.position_id, e.role_id, e.employee_id
				from tb_m_employee e
				join tb_r_overtime tr on e.employee_id = tr.employee_id
				join tb_r_overtime_reviewer trv on trv.ot_id = tr.ot_id
				where trv.reviewer_id = '".$employee_id."' group by e.company_id, e.work_unit_id, e.position_id, e.role_id, e.employee_id
			
				union 

				select e.company_id, e.work_unit_id, e.position_id, e.role_id, e.employee_id
				from tb_m_employee e
				join tb_r_other_request tr on e.employee_id = tr.employee_id
				join tb_r_other_request_reviewer trv on trv.other_request_id = tr.other_request_id
				where trv.reviewer_id = '".$employee_id."' group by e.company_id, e.work_unit_id, e.position_id, e.role_id, e.employee_id

			)e;

		";
		$result = $this->db->query($sql);
		if ($result->getNumRows() > 0)
		{
			return $result->getRow();
		}
		else
		{
			return false;
		}
	}

	public function getEncryptionKey()
	{
		$data = $this->db->table('tb_m_system_payroll')->where('system_type', SIDOKEY)->get()->getRow();
		return !empty($data) ? $data->system_value_txt : '';
	}
	
    public function getAccessData(
        $session_user_name = '',
        $session_employee_id = '',
        $user_group_id = array(),
		$is_admin = '',
        $delegate_flg = '0'
    )
    {
		$userGroupIdIn = implode(",", $user_group_id);
        $sqlDataAccess = "SELECT
                    usergroup_id,data_type,GROUP_CONCAT( DATAACCESS.data_id SEPARATOR ',' ) AS data_ids 
                FROM
                    tb_m_user_group_payroll_data_access DATAACCESS
                WHERE usergroup_id IN (?) GROUP BY data_type";
        $executeDataAccess = $this->db->query($sqlDataAccess,array($userGroupIdIn))->getResult();
		
        if(!$executeDataAccess) throw new Exception('Failed when execute get Employee, Line: 264');

        $whereDataAccess = array();
        if(!empty($executeDataAccess)){
            foreach ($executeDataAccess as $value) {
                $whereDataAccess[$value->data_type] = $value->data_ids;
            }
        }

        $sqlWhereEmployee = '';

		if(!empty($whereDataAccess))
		{
			$whereIn = function($value){
				return implode(',', array_unique(explode(",",$value)));
			};
			if($is_admin != '1')
			{
				$wheres = [];
				foreach ($whereDataAccess as $key => $value) {
					switch ($key) 
					{
						case 'AREA': $wheres[] = "work_unit_id IN ({$whereIn($value)})"; break;
						case 'COMPANY': $wheres[] = "company_id IN ({$whereIn($value)})"; break;
						case 'POSITION': $wheres[] = "position_id IN ({$whereIn($value)})"; break;
						case 'ROLE': $wheres[] = "role_id IN ({$whereIn($value)})"; break;
					}
				}
	
				$sqlWhereEmployee .= " AND (".implode(" AND ", $wheres).")";
			} else {
				$companies = '';
				$areas = '';
				$positions = '';
				$roles = '';
				$wheres = [];
				foreach ($whereDataAccess as $key => $value) {
					switch ($key) 
					{
						case 'COMPANY': $companies = $value; break;
						case 'AREA': $areas = $value; break;
						case 'POSITION': $positions = $value;break;
						case 'ROLE': $roles = $value; break;
					}
				}

				if(!empty($companies)){
					$wheres[] = "company_id IN ({$whereIn($companies)})";
				}

				// ** Area Access
				if(!empty($areas)){
					$areasArray = explode(",",$areas);
					if(!empty($areasArray)){
						$areas = implode(",",$areasArray);
					}
					$wheres[] = "work_unit_id IN (".$areas.")";
				} else {
					$companiesArray = explode(",",$companies);
					$areasArray = array();
					foreach ($companiesArray as $key => $value) {
						$sql = "SELECT work_unit_id FROM tb_m_work_unit WHERE company_id = '$value'";
						$workUnits = $this->db->query($sql)->getResultArray();
						$workUnitFiltered = array_map(function($item){
							return $item['work_unit_id'];
						}, $workUnits);

						array_push($areasArray, ...$workUnitFiltered);
					}

					if(!empty($areasArray)){
						$areas = implode(",",$areasArray);
					}
					$wheres[] = "work_unit_id IN (".$areas.")";
				}
				
				// ** Positions Access
				if(!empty($positions)){
					$positionsArray = explode(",",$roles);
					if(!empty($positionsArray)){
						$positions = implode(",",$positionsArray);
					}
					$wheres[] = "position_id IN (".$positions.")";
				} else {
					$companiesArray = explode(",",$companies);
					$positionsArray = array();
					foreach ($companiesArray as $key => $value) {
						$sql = "SELECT position_id FROM tb_m_position WHERE company_id = '$value'";
						$positions = $this->db->query($sql)->getResultArray();
						$positionFiltered = array_map(function($item){
							return $item['position_id'];
						}, $positions);

						array_push($positionsArray, ...$positionFiltered);
					}

					if(!empty($positionsArray)){
						$positions = implode(",",$positionsArray);
					}
					$wheres[] = "position_id IN (".$positions.")";
				}
				
				// ** Role Access
				if(!empty($roles))
				{
					$rolesArray = explode(",",$roles);
					if(!empty($rolesArray)){
						$roles = implode(",",$rolesArray);
					}
					$wheres[] = "role_id IN (".$roles.")";
				} else {
					$companiesArray = explode(",",$companies);
					$rolesArray = array();
					foreach ($companiesArray as $key => $value) {
						$sql = "SELECT role_id FROM tb_m_role WHERE company_id = '$value'";
						$roles = $this->db->query($sql)->getResultArray();
						$roleFiltered = array_map(function($item){
							return $item['role_id'];
						}, $roles);

						array_push($rolesArray, ...$roleFiltered);
					}

					if(!empty($rolesArray)){
						$roles = implode(",",$rolesArray);
					}
					$wheres[] = "role_id IN (".$roles.")";
				}

				$sqlWhereEmployee .= " AND (".implode(" AND ", $wheres).")";
			}
		}

        $sqlDeleteEmployeeDataAccess = "DELETE FROM tb_r_user_group_payroll_employee WHERE session_employee_id = ?";
        $executeDeleteEmployeeDataAccess = $this->db->query($sqlDeleteEmployeeDataAccess, array($session_employee_id));
        if(!$executeDeleteEmployeeDataAccess) throw new Exception('Failed when execute get Employee, Line: 301');

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
		
        if(!$executeGetEmployee) throw new Exception('Failed when execute get Employee, Line: 335');
    }

	public function getDelegate($email)
	{
		$sql = "SELECT user_group_id FROM tb_m_users_approver_delegation a LEFT JOIN tb_m_users b ON b.employee_id = a.employee_id WHERE a.user_email = ?";

		$data = $this->db->query($sql, array((string) $this->db->escapeString($email)))->getResultArray();

		if(!empty($data)){
			return array_unique(array_map(function($item) {
				return $item['user_group_id'];
			},$data));
		} else {
			return array();
		}
	}
}
