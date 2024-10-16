<?php

namespace App\Models\UserGroupAuth;

use App\Models\BaseModel;
use Exception;

class UserModel extends BaseModel {
	protected $compId;
	protected $groupId;
    protected $search_col;

    function __construct() {
        parent::__construct();
		$this->search_col = array(
            "TB.user_email",
            "TB.full_name",
            "TB.user_group_description",
            "TB.is_active_text",
            "TB.super_admin_text",
            "TB.email_confirmed_text",
        );
    }
	
	function query($keyword, $builder){
        $builder = $builder->table("(
			SELECT 
			(SELECT tmc.company_name  FROM tb_m_company tmc WHERE tmc.company_id = u.company_id ) AS company_name ,
				u.*
				,ug.user_group_description
				,CASE WHEN u.is_active = '1' THEN 'Aktif' ELSE 'Tidak Aktif' END AS is_active_text
				,CASE WHEN super_admin = '1' THEN 'Ya' ELSE 'Tidak' END AS super_admin_text
				,CASE WHEN u.email_confirmed = '1' THEN 'Sudah Konfirmasi' ELSE 'Belum Konfirmasi' END AS email_confirmed_text
				, m.employee_name
			FROM 
				tb_m_users u
			LEFT JOIN tb_m_user_group_payroll ug ON ug.user_group_id = u.user_group_payroll_id
			LEFT JOIN tb_m_employee m on m.employee_id = u.employee_id
		) TB");

		$i = 0;
		$where = "";
		$cnt = count($this->search_col) -1;
		foreach ($this->search_col as $item)
		{
			if ($i == 0){
					$where = "( ".$where . "UPPER(CAST(" . $item ." AS char)) LIKE UPPER('%$keyword%') ";
			}else if ($i == $cnt ){
					$where = $where ."OR " . "UPPER(CAST(" . $item ." AS char)) LIKE UPPER('%$keyword%') )";
			}else{
					$where = $where ."OR " . "UPPER(CAST(" . $item ." AS char)) LIKE UPPER('%$keyword%') ";
			}
			$i++;
		}

        $builder->where($where);
		
		if($this->compId){
        	$builder->where('company_id', $this->compId);
		}
		if($this->groupId){
        	$builder->where('user_group_payroll_id', $this->groupId);
		}

        return $builder;
    }
	
	function getData($start = '', $length = '', $keyword = '', $company_id = '', $user_group_id = null) {
		$this->compId = $company_id;		
		$this->groupId = $user_group_id;		
        $builder = $this->query($keyword, $this->db);
        $builder->orderBy('user_email');
        if ($start >= 0 && $length >= 0) {
            $builder->limit($length, $start);
        }
        return $builder->get()->getResult();
    }
	
    function countData($keyword = '', $company_id =  null, $user_group_id = null){
    	if($company_id){

    		$this->compId = $company_id;
    	}
		$this->groupId = $user_group_id;	
        $builder = $this->query($keyword, $this->db);
        return $builder->countAllResults();
       
    }
	
	//=======================================================================================
	
	function get_user_group() {
        $builder = $this->db->table('tb_m_user_group_payroll');
        $builder->select('*');
        $builder->where('company_id', $this->session->get(S_COMPANY_ID));
        return $builder->get()->getResult();
    }
	
	function check_email($user_email) {
        $sql = "SELECT * FROM tb_m_users where user_email = '".$user_email."'";
        $query = $this->db->query($sql);
        if ($query->getNumRows() > 0) {
            return 'false';
        } else {
            return 'true';
        }
    }

    function saveData($mode, $data, $id = "") {
		$reset_flg = (!empty($this->input->getPost("reset_flg"))) ? "1" : "0";
		
        if ($mode == 'insert') {
            $builder = $this->db->table('tb_m_users');
            $builder->insert($data);
            return $this->db->affectedRows();
        } else {
			if($reset_flg == "1"){
				$data["employee_id"] = 0;

				$this->resetEmail($id);
			}

            $builder = $this->db->table('tb_m_users');
            $builder->where('user_email', $id);
            // $builder->where('company_id', $this->session->get(S_COMPANY_ID));
            $builder->update($data);

			
            return $this->db->affectedRows();
        }
    }

	function resetEmail($id){
        $builder = $this->db->table('tb_m_employee');
		$builder->where('user_email', $id);
		$builder->update(array("user_email" => NULL));
	}
	
	function getDataById($id) {
		$S_ACCESS_COMPANY_ID = $this->session->get(S_ACCESS_COMPANY_ID);
		$sql = "SELECT 
			u.*
			,ug.user_group_description
			,CASE WHEN u.is_active = '1' THEN 'Aktif' ELSE 'Tidak Aktif' END AS is_active_text
			,CASE WHEN super_admin = '1' THEN 'Ya' ELSE 'Tidak' END AS super_admin_text
			,CASE WHEN u.email_confirmed = '1' THEN 'Sudah Konfirmasi' ELSE 'Belum Konfirmasi' END AS email_confirmed_text
			, m.employee_name
		FROM 
			tb_m_users u
		LEFT JOIN tb_m_user_group_payroll ug ON ug.user_group_id = u.user_group_payroll_id
		LEFT JOIN tb_m_employee m on m.employee_id = u.employee_id
		WHERE 
			md5(u.user_email) = '".$id."'";
		$sql .= ($S_ACCESS_COMPANY_ID != '') ? " and u.company_id in (" . $S_ACCESS_COMPANY_ID . ")" : '';
        
		$query = $this->db->query($sql);

		return $query->getRow();
    }

    function delete($user_email) {
        try {
            $builder = $this->db->table('tb_m_users');
			$builder->where('user_email', $user_email);
			// $this->db->where('company_id', $this->session->get(S_COMPANY_ID));
			$builder->delete();
			
			// remove from employee
			$this->db->query("update tb_m_employee set user_email = null, work_email = null where user_email = '" . $user_email . "'");
			
            return true;
		} catch (Exception $e) {								
			return false;		
		}
    }
	
	function do_confirm($email_confirmed_code){
		$data =  array(
			'email_confirmed' => 1
		);

        $builder = $this->db->table('tb_m_users');
        $builder->where('email_confirmed_code', $email_confirmed_code);
        $builder->update($data);

		return $this->db->affectedRows();
	}
	
	// 20170407 irfan@arkamaya.com
	// untuk mengambil data employee yang belum di assign user nya
	function get_employee_to_assign()
	{
		$sql = "select a.employee_id, a.employee_name, a.user_email, a.work_email
				from 
					tb_m_employee a 
				where 
					a.company_id = '".$this->session->get(S_COMPANY_ID)."' 
					and (a.user_email is null or a.work_email is null)
				order by a.employee_name
				";
		return $this->db->query($sql)->getResult();
	}
	
	// 20170407 irfan@arkamaya.com 
	// untuk cek jumlah user yg diperbolehkan sesuai paket
	function get_usercount()
	{
		$sql = "select default_users from tb_m_package a 
			inner join tb_m_company b on b.package_id = a.package_id 
			where b.company_id = '".$this->session->get(S_COMPANY_ID)."'";
		return $this->db->query($sql)->getRow()->default_users;
	}

	// add by arka.budi 01/03/2019
	// jumlah pegawai yg aktif
	function countData_emplo(){
		$sql = "
			select 
				count(1) as cnt_emplo
			from 
				tb_m_employee a 
			where 
				a.company_id = '".$this->session->get(S_COMPANY_ID)."' 
				and a.is_active = 1
		";
		
		return $this->db->query($sql)->getRow()->cnt_emplo;
    }

    function get_employee_by_company($company_id)
	{
		$sql = "select a.employee_id, a.employee_name, a.user_email, a.work_email
				from 
					tb_m_employee a 
				where 
					a.company_id = '".$company_id."' 
					and (a.user_email is null or a.work_email is null)
				order by a.employee_name
				";
		return $this->db->query($sql)->getResult();
	}

	function get_user_group_by_company_id($company_id) {
        $builder = $this->db->table('tb_m_user_group_payroll');
        $builder->select('*');
        $builder->where('company_id', $company_id);
        return $builder->get()->getResult();
    }
	

	function getUserGroups($company_id, $keyword){
		$S_ACCESS_COMPANY_ID = $this->session->get(S_ACCESS_COMPANY_ID);
		$sql = "";
        $sql .= "
				select '0' as id, 'All Group' as text UNION ALL 
				select 
					t1.user_group_id as id
					, concat(ifnull(t2.company_code, 'No Code'), ' - ', t1.user_group_description) as text 
				from tb_m_user_group_payroll t1 
				inner join tb_m_company t2 on t1.company_id = t2.company_id
				where 1 = 1";
		if(!empty($company_id) && $company_id != "0"){

			$sql .= " and t1.company_id = " . $company_id;
		}
		if($keyword != ""){
			$sql .= " and (t2.company_code like '%".$this->db->escapeString($keyword)."%'
					or t1.user_group_description like '%".$this->db->escapeString($keyword)."%')";
		}

		$sql .= ($S_ACCESS_COMPANY_ID != '') ? " and t1.company_id in (" . $S_ACCESS_COMPANY_ID . ")" : '';

        $query = $this->db->query($sql);

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return false;
        }
	}
}
