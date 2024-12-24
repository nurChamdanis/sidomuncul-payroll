<?php

namespace App\Models\UserGroupAuth;

use App\Models\BaseModel;

class UserGroupModel extends BaseModel 
{
	function get_user_groups($start = '', $length = '', $order_by = 'user_group_description asc', $company_id = '')
	{			
		$company_id = ($company_id == '') ? $this->session->get(S_COMPANY_ID) : $company_id;
		$sql = "
			select 
				*
			from
				tb_m_user_group_payroll
			where
				company_id = '" . $company_id . "'
		";
		$sql .= ' order by ' . $order_by;
		
		if ($start != '' && $length != '')
		{
			$sql .= ' limit ' . $start . ', ' . $length;
		}
		
		return $this->db->query($sql)->getResult();
    }
	
	function get_user_group_count($order_by = 'user_group_description asc')
	{		
		$sql = "
			select 
				count(user_group_id) as cnt
			from
				tb_m_user_group_payroll
			where
				company_id = '" . $this->session->get(S_COMPANY_ID) . "'
		";
		
		$sql .= ' order by ' . $order_by;
		
		return $this->db->query($sql)->getRow()->cnt;
    }
	
	function get_user_group($user_group_id)
	{	
			
		$sql = "
			select 
				*
			from
				tb_m_user_group_payroll
			where
				company_id = '" . $this->session->get(S_COMPANY_ID) . "'
				and user_group_id = '" . $user_group_id . "'
		";				
		
		return $this->db->query($sql)->getResult();
    }
	
	function getData($start = '', $length = '', $keyword = '', $company_id = '') {
        $query = $this->db->table('tb_m_user_group_payroll ug')
            ->select('ug.user_group_id, ug.user_group_description, ug.is_admin, ug.default_landing, f.function_name, c.company_name')
            ->join('tb_m_function_payroll f', "ug.default_landing != '' and ug.default_landing = f.function_controller",'left')
            ->join('tb_m_company c', 'c.company_id = ug.company_id','left')
            ->orderBy('c.company_name, ug.user_group_description');

        if ($company_id != '' && $company_id != '-') {
            $query->where('ug.company_id', $company_id);
        }

        if ($keyword != '') {
            $query->groupStart()
                ->like('ug.user_group_description', $keyword)
                ->orLike('c.company_name', $keyword)
                ->orLike('f.function_name', $keyword)
                ->groupEnd(); 
        }

        if ($start >= 0 && $length >= 0) {
            $query->limit($length, $start);
        }

        return $query->get()->getResult();
    }

    function countData($keyword = '', $company_id = '') {
        $builder = $this->db->table('tb_m_user_group_payroll ug');
        $builder->select('ug.user_group_id');
        $builder->join('tb_m_function_payroll f', "ug.default_landing != '' and ug.default_landing = f.function_controller", 'left');
        
        if (!empty($company_id) && $company_id != '-') {
            $builder->where('ug.company_id', $company_id);
        }
        if (!empty($keyword)) {
            $builder->groupStart()
                    ->like('ug.user_group_description', $keyword)
                    ->orLike('c.company_name', $keyword)
                    ->orLike('f.function_name', $keyword)
                    ->groupEnd();
        }
        $builder->join('tb_m_company c', "c.company_id = ug.company_id", 'inner');
        // Uncomment the next line if you want to filter by session company_id
        // $builder->where('ug.company_id', session('S_COMPANY_ID'));
        return $builder->countAllResults();
        
    }

    function getDataById($id) {
        $builder = $this->db->table('tb_m_user_group_payroll ug');
        $builder->select('ug.user_group_id,, ug.user_group_description, ug.is_admin, ug.default_landing, count(e.employee_id) as jmlh_pegawai, f.function_name, ug.company_id');
		$builder->join('tb_m_users u', 'u.user_group_payroll_id = ug.user_group_id', 'left');
        $builder->join('tb_m_employee e', 'e.user_email = u.user_email', 'left');
        $builder->join('tb_m_function_payroll f', "ug.default_landing != '' and ug.default_landing = f.function_controller" , 'left');
        $builder->where('ug.user_group_id', $id);
        //return $builder->get()->result();
        return $builder->get()->getRow();
    }
    function saveData($mode, $data, $id = "") {
        if ($mode == 'insert') {
            // insert user group
            $builder = $this->db->table('tb_m_user_group_payroll');
            $builder->insert($data);

            //$this->insert_details($this->db->insert_id());
            return $this->db->insertID();
        } else {
            $builder = $this->db->table('tb_m_user_group_payroll');
            $builder->where('user_group_id', $id);
            // $this->db->where('company_id', $this->session->get(S_COMPANY_ID));
            $builder->update($data);

            // delete items
            //$this->db->where('role_id', $role_id);		
            //$this->db->delete('tb_m_role_salary');		
            // insert again
            //$this->insert_details($role_id);
            return $this->db->affectedRows();
        }
    }

    function saveDataUserGroupDataAuth($mode, $data, $user_group_id = ''){
        if($mode == 'insert'){
            $builder = $this->db->table('tb_m_user_group_payroll_data_auth');
            $builder->insert($data);
        }else{
            $builder = $this->db->table('tb_m_user_group_payroll_data_auth');
            $builder->where('usergroup_id', $user_group_id);
            $builder->update($data);
        }
    }

    function delete($user_group_id) {
        $builder = $this->db->table('tb_m_user_group_payroll_data_access');
        $builder->where('usergroup_id', $user_group_id);
        $builder->delete();

        
        $builder = $this->db->table('tb_m_user_group_payroll_data_auth');
        $builder->where('usergroup_id', $user_group_id);
        $builder->delete();

        $builder = $this->db->table('tb_m_user_group_payroll_auth');
        $builder->where('user_group_id', $user_group_id);
        $builder->delete();
        
        $builder = $this->db->table('tb_m_user_group_payroll');
        $builder->where('user_group_id', $user_group_id);
        $builder->delete();
    }

    function saveUserGroupDataAccess($data_type, $user_group_id, $data){
       // Delete existing data
        $builder = $this->db->table('tb_m_user_group_payroll_data_access');
        $builder->where('data_type', $data_type);
        $builder->where('usergroup_id', $user_group_id);
        $builder->delete();

        // Insert new data if there are any
        if (!empty($data)) {
            $insert = $this->db->table('tb_m_user_group_payroll_data_access')->insertBatch($data);
        }
    }

    function updateUsergroupAuth($data, $usergroup_id, $function_id) {
        //delete from  tb_m_user_group_payroll_auth
        $this->db->table('tb_m_user_group_payroll_auth')->where('user_group_id', $usergroup_id)->where('function_id', $function_id)->delete();
        //insert data
        if ($data) {
            $insert = $this->db->table('tb_m_user_group_payroll_auth')->insertBatch($data);
        }
        return $this->db->affectedRows();
    }

    function getFunctionListDatatable($start = '', $length = '', $keyword = '') {
        $builder = $this->db->table('tb_m_function_payroll f');
        $builder->select('f.function_id, f.function_parent, f.function_name, f.function_name_id, f.function_active, f.function_controller , case when f.function_parent = 0 then f.function_id else f.function_parent end as sort', false);
        
        // $this->db->join('tb_m_company_function cf', 'f.function_id = cf.function_id and cf.company_id =' . $this->session->get(S_COMPANY_ID), 'inner');
        $builder->where('f.function_active', 1);
        if ($keyword != '') {
            $builder->like('f.function_name', $keyword);
        }

        if ($this->session->get(S_IS_ADMIN) == '1') {
            $builder->where('f.function_controller != "user_group"');
        }

//        else{
//            $builder->where('f.function_parent is null');
//            $builder->or_where('f.function_parent', 0);
//        }
        if ($start >= 0 && $length >= 0) {
            $builder->limit($length, $start);
        }

        $builder->orderBy('sort, function_id');
        return $builder->get()->getResult();
        //$this->output->enable_profiler(true);
    }

    function countDataFunction($keyword = '') {
        $builder = $this->db->table('tb_m_function_payroll f');
        $builder->select('f.function_id');
        // $builder->join('tb_m_company_function cf', 'f.function_id = cf.function_id and cf.company_id =' . $this->session->get(S_COMPANY_ID), 'inner');
        $builder->like('f.function_name', $keyword);
        $builder->where('f.function_active', 1);
        if ($this->session->get(S_IS_ADMIN) == '1') {
            $builder->where('f.function_controller != "user_group"');
        }
        return $builder->countAllResults();
    }

    function getFunctionList($function_id = '') {
        $builder = $this->db->table('tb_m_function_payroll f');
        $builder->select('f.function_id, f.function_parent, f.function_name, f.function_name_id, f.function_active, f.function_controller , case when f.function_parent = 0 then f.function_id else f.function_parent end as sort', false);
        // $builder->join('tb_m_company_function cf', 'f.function_id = cf.function_id and cf.company_id =' . $this->session->get(S_COMPANY_ID), 'inner');
        $builder->where('f.function_active', 1);
        if ($function_id != '') {
            $builder->where('f.function_i', $function_id);
        }

        if ($this->session->get(S_IS_ADMIN) == '1') {
            $builder->where('f.function_controller != "user_group"');
        }

//        else{
//            $builder->where('f.function_parent is null');
//            $builder->or_where('f.function_parent', 0);
//        }
        $builder->orderBy('sort, function_id');
        return $builder->get()->getResult();
        //$this->output->enable_profiler(true);
    }

    function getFeatureList($function_id, $usergroup, $type) {
        $_join = ($type == "view") ? "inner" : "left";
        $builder = $this->db->table('tb_m_function_feature_payroll ff');
        $builder->select('ff.feature_id, ff.function_id, ff.feature_name, ff.feature_element_type, ff.feature_description, case when uga.user_group_id is not null then 1 else 0 end as is_used', false);
        $builder->join('tb_m_user_group_payroll_auth uga', 'ff.feature_id = uga.feature_id and ff.function_id = uga.function_id  and uga.user_group_id = ' . $usergroup, $_join);
		$builder->where('ff.function_id', $function_id);
		$builder->orderBy('ff.feature_element_type desc, ff.feature_name');
        $data =  $builder->get()->getResult();
        return $data;
    }
    
    function getFunctionId($feature_id) {
        $builder = $this->db->table('tb_m_function_feature_payroll ff');
        $builder->select('ff.feature_id, ff.function_id', false);
		$builder->where('ff.feature_id', $feature_id);
        return $builder->get()->getRow()->function_id;
    }
    
    function updateUsergroupAuthNew($data, $usergroup_id) {
        //delete from  tb_m_user_group_payroll_auth
        $this->db->table('tb_m_user_group_payroll_auth')->where('user_group_id', $usergroup_id)->delete();
        //insert data
        if ($data) {
            $this->db->table('tb_m_user_group_payroll_auth')->insertBatch($data);
        }
        return $this->db->affectedRows();
    }
    
    function getCompanyList() {
        $builder = $this->db->table('tb_m_company c');
        $builder->select('c.company_id, c.company_name', false);
        $builder->orderBy('company_name');
        return $builder->get()->getResult();
    }

    function getWorkUnitList($company_id = '') {
        $builder = $this->db->table('tb_m_work_unit w');
        $builder->select('w.work_unit_id, w.company_id, w.name', false);
        if ($company_id != '') {
            $builder->where('w.company_id', $company_id);
        }

        $builder->orderBy('name');
        return $builder->get()->getResult();
    }

    function getPositionList($company_id = '') {
        $builder = $this->db->table('tb_m_position p');
        $builder->select('p.position_id, p.company_id, p.position_name', false);
        if ($company_id != '') {
            $builder->where('p.company_id', $company_id);
        }
        $builder->orderBy('position_name');
        return $builder->get()->getResult();
    }
    
    function getRoleList($company_id = '') {
        $builder = $this->db->table('tb_m_role r');
        $builder->select('r.role_id, r.company_id, r.role_name', false);
        if ($company_id != '') {
            $builder->where('r.company_id', $company_id);
        }

        $builder->orderBy('role_name');
        return $builder->get()->getResult();
    }
    
    function getDataAuth($usergroup_id = '') {
        $builder = $this->db->table('tb_m_user_group_payroll_data_auth a');
        $builder->select('a.usergroup_id, a.related_area_flg, a.related_position_flg, a.related_role_flg, a.subordinate_flg', false);
        if ($usergroup_id != '') {
            $builder->where('a.usergroup_id', $usergroup_id);
        }
        return $builder->get()->getRow();
    }
    
    function getDataAccess($usergroup_id = '', $data_type = '') {
        $builder = $this->db->table('tb_m_user_group_payroll_data_access');
        $builder->select('usergroup_id, data_type, data_id', false);
        if ($usergroup_id != '') {
            $builder->where('usergroup_id', $usergroup_id);
        }
        if ($data_type != '') {
            $builder->where('data_type', $data_type);
        }
        return  $builder->get()->getResult();
    }
    
	function getSystem($system_type = '', $system_cd = '') {
        $builder = $this->db->table('tb_m_system');
        $builder->select('system_type, system_code, system_code_desc, system_value_txt, system_value_num, system_value_time');
        if ($system_type != ''){
            $builder->where('system_type', $system_type);
        }
        if ($system_cd != ''){
            $builder->where('system_code', $system_cd);
        }
        // $builder->order_by('c.company_name, user_group_description');
        return $builder->get()->getResult();
    }

	
	function get_used_user_count($user_group_id = '')
	{		
		$sql = "
			select 
				count(user_group_id) as cnt
			from
				tb_m_users
			where
            user_group_id = '" . $user_group_id . "'
		";
		
		return $this->db->query($sql)->getRow()->cnt;
    }

    /*
     * @updated by : arka.septian
     * @date : 2023-09-08
     * */

    function get_user_groups_by_company_id($start = '', $length = '', $order_by = 'user_group_description asc', $company_id = '')
    {
        $sql = "
            select 
                *
            from
                tb_m_user_group_payroll
            where
                company_id = '" . $company_id . "'
        ";
        $sql .= ' order by ' . $order_by;

        if ($start != '' && $length != '')
        {
            $sql .= ' limit ' . $start . ', ' . $length;
        }

        return $this->db->query($sql)->getResult();
    }
    function getUserGroup($except = array()){
        $COMPANY_ACCESS = $this->session->get(S_ACCESS_COMPANY_ID);
        $fields = ['user_group_id', 'user_group_description', 'company_code'];
        $query = $this->db->table('tb_m_user_group_payroll')
            ->select($fields)
            ->join('tb_m_company', 'tb_m_company.company_id = tb_m_user_group_payroll.company_id', 'left')
            ->whereIn('tb_m_user_group_payroll.company_id', explode(',', $COMPANY_ACCESS))
            ->whereNotIn('user_group_id', $except);
        return $query->get()->getResult();
    }
}
?>