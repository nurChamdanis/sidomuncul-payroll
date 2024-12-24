<?php

namespace App\Controllers;

/**
 * @author misbah@arkamaya.co.id
 * @since 2017-02-06 
 * --------------------------------
 * @modified luthfi.aziz@arkamaya.co.id on May 2024
 */

use App\Core\MyController;
use App\Models\Shared\SharedModel;
use App\Models\UserGroupAuth\UserGroupModel;
use CodeIgniter\HTTP\URI;

class Usergroup extends MyController {
    protected $shared_model;
    protected $ugm;
    
    function __construct() {
        parent:: __construct();

        $this->shared_model = new SharedModel();
        $this->ugm = new UserGroupModel();
    }

    function index() {
        $data = array_merge(get_title($this->menu, 'usergroup'), $this->data);
        $data['jsapp'] = array('user_group');
        $data['stitle'] = 'Grup Pengguna';
        $data['company'] = $this->ugm->getCompanyList();

        return view('usergroup/user_group', $data);
    }

    function getData() {
        $order = !empty($this->input->getPost('order')) ? $this->db->escapeString($this->input->getPost('order')) : '';
        $column = $this->input->getPost('columns');
        // $idx_cols = $order[0]['column'];

        $def = array(
            'draw' => $this->db->escapeString($this->input->getPost('draw')),
            'length' => $this->db->escapeString($this->input->getPost('length')),
            'start' => $this->db->escapeString($this->input->getPost('start')),
        );
        
        $start = isset($_POST['start']) ? intval($this->db->escapeString($_POST['start'])) : 0;
        $length = isset($_POST['length']) ? intval($this->db->escapeString($_POST['length'])) : 50;
        $keyword = $this->db->escapeString($this->input->getPost('search')['value']);
        $company_id = $this->db->escapeString($this->input->getPost('company_id'));
        
        $results = $this->ugm->getData($start, $length, $keyword, $company_id);
        $recordsTotal = $this->ugm->countData($keyword, $company_id);

        $data = array();
        foreach ($results as $r) {
            $row = array();
            $row[] = htmlspecialchars($r->company_name);
            $row[] = '<a href="' . site_url('usergroup/edit/' . htmlspecialchars($r->user_group_id)) . '" title="Lihat User Group">' . htmlspecialchars($r->user_group_description) . '</a>';
            $row[] = htmlspecialchars($r->function_name);
            $data[] = $row;
        }

        $output = array(
            "draw" => htmlspecialchars($def['draw']),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsTotal,
            "data" => $data
        );
        echo json_encode($output);
    }

    function geFunctionFeature() {
        $order = $this->db->escapeString($this->input->getPost('order'));
        $column = $this->input->getPost('columns');
        // $idx_cols = $order[0]['column'];

        $def = array(
            'draw' => $this->db->escapeString($this->input->getPost('draw')),
            'length' => $this->db->escapeString($this->input->getPost('length')),
            'start' => $this->db->escapeString($this->input->getPost('start')),
        );
        
        
        
        $start = isset($_POST['start']) ? intval($this->db->escapeString($_POST['start'])) : 0;
        $length = isset($_POST['length']) ? intval($this->db->escapeString($_POST['length'])) : 50;
        $keyword = $this->db->escapeString($this->input->getPost('search')['value']);
        
        $results = $this->ugm->getFunctionListDatatable($start, $length, $keyword);
        $recordsTotal = $this->ugm->countDataFunction($keyword);

        $data = array();
        foreach ($results as $r) {

            if ($r->function_parent == "0" || $r->function_parent == "null")  {
                $text = "<strong>".strtoupper(htmlspecialchars($r->function_name))."</strong>";
            } else {
                $text = htmlspecialchars($r->function_name);
            }
            $row = array();
            $row[] = $text;
            $row[] = '<button class="btn btn-sm btn-icon waves-effect waves-light btn-primary edit" type="button" onclick="setFeature(`'.$r->function_id.'`,`'.$r->function_name.'`)" style="z-index: 0 !important;"><i class="fa fa-gear"></i> Pengaturan</button>';
            $data[] = $row;
        }

        $output = array(
            "draw" => htmlspecialchars($def['draw']),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsTotal,
            "data" => $data
        );
        echo json_encode($output);
    }
    
    function getDataFunction(){
        
        $function_id = $this->db->escapeString($this->input->getPost('function_id'));
        
        $function_list = $this->ugm->getFunctionList($function_id);
        $fl = array();
        foreach ($function_list as $r) {
            $row = array();
            $is_active = (htmlspecialchars($r->function_active)=="1") ? "checked" : "";
            $row[] = '';
            $row[] = htmlspecialchars($r->function_name);
            $row[] = '<div class="checkbox checkbox-default"><input id="checkbox3" type="checkbox" '.$is_active.'> <label for="checkbox3"></label></div>';
            $row[] = '';
            $fl[] = $row;
        }
        echo json_encode($fl);
    }
    function getDataFeature(){
        
        $function_id = $this->db->escapeString($this->input->getPost('function_id'));
        $usergroup = $this->db->escape($this->input->getPost('usergroup'));
        $type = $this->db->escapeString($this->input->getPost('type'));
        
        $feature_list = $this->ugm->getFeatureList($function_id, $usergroup, $type);
        $fl = array();
        $i = 1;
		
		$arr_eltype = array(
			'page' => 'Halaman'
			, 'button' => 'Tombol'
			, 'button_group' => 'Grup Tombol'
			, 'button_group_list' => 'Daftar Grup Tombol'
			, 'submit' => 'Tombol Submit'
			, '' => 'Umum'
		);
		
        foreach ($feature_list as $r) {
            $checked = (htmlspecialchars($r->is_used) == "1") ? "checked" : "";
            $row = array();
            $row[] = '<div class="checkbox checkbox-default"><input id="cb'.$i.'" class="chkFeature" type="checkbox" '.$checked.'> <label for="cb'.$i.'"></label></div>';
            $row[] = (isset($arr_eltype[htmlspecialchars($r->feature_element_type)])) ? $arr_eltype[htmlspecialchars($r->feature_element_type)] : htmlspecialchars($r->feature_element_type);
            $row[] = htmlspecialchars($r->feature_description);
            $row[] = htmlspecialchars($r->feature_id);
            $fl[] = $row;
            $i++;
        }
        echo json_encode($fl);
    }
    
    function id() {
        if (isset($_POST['user_group_id']))
        {
            $this->ugm->delete($this->db->escapeString($_POST['user_group_id']));
            $this->session->setFlashdata('notif_status', 'error');
            $this->session->setFlashdata('notif_resend', '<strong>Gagal. </strong> terjadi kesalahan saat mengirimkan link aktivasi, silahkan coba lagi.');$this->session->setFlashdata('notif_user_group_success', '<strong>Berhasil.</strong> Grup Pengguna Berhasil dihapus.');
            return redirect()->to('/usergroup');
        }
        
        $user_group_id = $this->uri->setSilent()->getSegment(3);
        if ($user_group_id == '') {
            return redirect()->to('/usergroup');
        }

        $user_group = $this->ugm->getDataById($user_group_id);
        if (!$user_group) {
            return redirect()->to('/usergroup');
        }

        $data = array_merge(get_title($this->menu, 'usergroup'), $this->data);
        $data['user_group'] = $user_group;
        $data['function_list'] = $this->ugm->getFunctionList();
        $data['jsapp'] = array('user_group_id');

        return view('usergroup/user_group_id',$data);
    }

    function create() {
        if (isset($_POST['user_group_id'])) {
            $user_group_id = htmlspecialchars($this->db->escapeString($this->input->getPost('user_group_id')));
            $company_id = htmlspecialchars($this->db->escapeString($this->input->getPost('company_id')));
            $data2 = array(
                'company_id' => $company_id
                , 'user_group_id' => $user_group_id
                , 'is_admin' => ($this->input->getPost('is_admin')) ? "1" : "0"
                , 'user_group_description' => htmlspecialchars($this->db->escapeString($this->input->getPost('user_group_description')))
                , 'default_landing' => htmlspecialchars($this->db->escapeString($this->input->getPost('default_landing')))
            );
            
            if ($user_group_id == '' || $user_group_id == null) {
                $mode = 'insert';
                $data2['created_by'] = $this->db->escapeString($this->session->get(S_USER_NAME));
                $data2['created_dt'] = date('Y-m-d H:i:s');
            } else {
                $mode = 'update';
                $data2['changed_by'] = $this->db->escapeString($this->session->get(S_USER_NAME));
                $data2['changed_dt'] = date('Y-m-d H:i:s');
            }
            
            $user_group_id = $this->ugm->saveData($mode, $data2);
            
            $userGroupDataAuth = array(
                'usergroup_id' => $user_group_id
                , 'related_area_flg' => '0'
                , 'related_position_flg' => '0'
                , 'related_role_flg' => '0'
                , 'subordinate_flg' => '0'
                , 'created_by' => $this->db->escapeString($this->session->get(S_USER_NAME))
                , 'created_dt' => date('Y-m-d H:i:s')
            );
            $this->ugm->saveDataUserGroupDataAuth('insert', $userGroupDataAuth);

            // Insert default company
            $row = array(
                'usergroup_id' => $user_group_id
                , 'data_type' => "COMPANY"
                , 'data_id' => $company_id
                , 'created_by' => $this->db->escapeString($this->session->get(S_USER_NAME))
                , 'created_dt' => date('Y-m-d H:i:s')
            );
            $dataSave[] = $row;
            $res = $this->ugm->saveUserGroupDataAccess("COMPANY", $user_group_id, $dataSave);


            $this->session->setFlashdata('notif_user_group_success', '<strong>Berhasil.</strong> Grup Pengguna Berhasil dibuat. Silahkan Atur Menu yang bisa diakses oleh grup ini');
            
            return redirect()->to(new URI(base_url() . 'usergroup/edit/' . $user_group_id));
        }
        
        $data = array_merge(get_title($this->menu, 'usergroup'), $this->data);
        $data['function_list'] = $this->ugm->getFunctionList();
        $data['company_list'] = $this->ugm->getCompanyList();
        $data['jsapp'] = array('user_group_create');

        return view('usergroup/user_group_create', $data);
    }

    function edit() {
        $data = array_merge(get_title($this->menu, 'usergroup'), $this->data);
        
		if (isset($_POST['usergroup_action'])) {
            $user_group_id = $this->db->escapeString($this->input->getPost('user_group_id'));
            $usergroup_action = $this->db->escapeString($this->input->getPost('usergroup_action'));
            if($usergroup_action == 'delete'){
                $this->ugm->delete($user_group_id);
                $this->session->setFlashdata('notif_user_group_success', '<strong>Berhasil.</strong> Grup Pengguna Berhasil dihapus.');
                return redirect()->to('/usergroup');
            }
		}

        if (isset($_POST['user_group_id'])) {
            $user_group_id = $this->db->escapeString($this->input->getPost('user_group_id'));
            $_data = array(
                'user_group_description' => $this->db->escapeString($this->input->getPost('user_group_description'))
                , 'default_landing' => $this->db->escapeString($this->input->getPost('default_landing'))
                , 'is_admin' => ($this->input->getPost('is_admin')) ? "1" : "0"
                , 'changed_by' => $this->db->escapeString($this->session->get(S_USER_NAME))
                , 'changed_dt' => date('Y-m-d H:i:s')
            );
            $mode = 'update';
            $this->ugm->saveData($mode, $_data, $user_group_id);

            // edited : arka.moharifrifai 2023-06-26 - save data feature
            $feature_list = $this->db->escapeString($this->input->getPost('feature_list'));        
            $feature_id = explode(',', $feature_list);
            $data2 = array();
            if (count($feature_id)>0){
                foreach($feature_id as $id){
                    if($id != ''){
                        $row = array(
							'user_group_id' => $user_group_id
							, 'function_id' => $this->ugm->getFunctionId($id)
							, 'feature_id' => $id
							, 'created_by' => $this->db->escapeString($this->session->get(S_USER_NAME))
							, 'created_dt' => date('Y-m-d H:i:s')
							, 'changed_by' => $this->db->escapeString($this->session->get(S_USER_NAME))
							, 'changed_dt' => date('Y-m-d H:i:s')
						);
						$data2[] = $row;
                    }
                }
            }
            $res = $this->ugm->updateUsergroupAuthNew($data2, $user_group_id);

            // tb_m_user_group_data_auth
            $related_area_flg = $this->db->escapeString($this->input->getPost('related_area_flg'));        
            $related_position_flg = $this->db->escapeString($this->input->getPost('related_position_flg'));        
            $related_role_flg = $this->db->escapeString($this->input->getPost('related_role_flg'));        
            $subordinate_flg = $this->db->escapeString($this->input->getPost('subordinate_flg'));        
            $userGroupDataAuth = array(
                'usergroup_id' => $user_group_id
                , 'related_area_flg' => $related_area_flg
                , 'related_position_flg' => $related_position_flg
                , 'related_role_flg' => $related_role_flg
                , 'subordinate_flg' => $subordinate_flg
                , 'changed_by' => $this->db->escapeString($this->session->get(S_USER_NAME))
                , 'changed_dt' => date('Y-m-d H:i:s')
            );
            $this->ugm->saveDataUserGroupDataAuth('edit', $userGroupDataAuth, $user_group_id);
            
            // tb_m_user_group_data_access COMPANY
            $data_access_company_list = $this->db->escapeString($this->input->getPost('data_access_company_list'));        
            $data_access_company_list = explode(',', $data_access_company_list);
            $insert_data_company_access = array();
            $data_type = 'COMPANY';
            if (count($data_access_company_list)>0){
                foreach($data_access_company_list as $id){
                    if($id != ''){
                        $row = array(
							'usergroup_id' => $user_group_id
							, 'data_type' => $data_type
							, 'data_id' => (int)$id
							, 'created_by' => $this->db->escapeString($this->session->get(S_USER_NAME))
							, 'created_dt' => date('Y-m-d H:i:s')
						);
                        $insert_data_company_access[] = $row;
                    }
                }
            }
            $res = $this->ugm->saveUserGroupDataAccess($data_type, $user_group_id, $insert_data_company_access);


            // tb_m_user_group_data_access AREA
            $data_access_area_list = $this->db->escapeString($this->input->getPost('data_access_area_list'));        
            $data_access_area_list = explode(',', $data_access_area_list);
            $insert_data_access_area = array();
            $data_type = 'AREA';
            if (count($data_access_area_list)>0){
                foreach($data_access_area_list as $id){
                    if($id != ''){
                        $row = array(
							'usergroup_id' => $user_group_id
							, 'data_type' => $data_type
							, 'data_id' => (int)$id
							, 'created_by' => $this->db->escapeString($this->session->get(S_USER_NAME))
							, 'created_dt' => date('Y-m-d H:i:s')
						);
                        $insert_data_access_area[] = $row;
                    }
                }
            }
            $res = $this->ugm->saveUserGroupDataAccess($data_type, $user_group_id, $insert_data_access_area);
            
            // tb_m_user_group_data_access POSITION
            $data_access_position_list = $this->db->escapeString($this->input->getPost('data_access_position_list'));        
            $data_access_position_list = explode(',', $data_access_position_list);
            $insert_data_position_access = array();
            $data_type = 'POSITION';
            if (count($data_access_position_list)>0){
                foreach($data_access_position_list as $id){
                    if($id != ''){
                        $row = array(
							'usergroup_id' => $user_group_id
							, 'data_type' => $data_type
							, 'data_id' => (int)$id
							, 'created_by' => $this->db->escapeString($this->session->get(S_USER_NAME))
							, 'created_dt' => date('Y-m-d H:i:s')
						);
                        $insert_data_position_access[] = $row;
                    }
                }
            }
            $res = $this->ugm->saveUserGroupDataAccess($data_type, $user_group_id, $insert_data_position_access);
            
            // tb_m_user_group_data_access ROLE
            $data_access_role_list = $this->db->escapeString($this->input->getPost('data_access_role_list'));        
            $data_access_role_list = explode(',', $data_access_role_list);
            $insert_data_access_role = array();
            $data_type = 'ROLE';
            if (count($data_access_role_list)>0){
                foreach($data_access_role_list as $id){
                    if($id != ''){
                        $row = array(
							'usergroup_id' => $user_group_id
							, 'data_type' => $data_type
							, 'data_id' => (int)$id
							, 'created_by' => $this->db->escapeString($this->session->get(S_USER_NAME))
							, 'created_dt' => date('Y-m-d H:i:s')
						);
                        $insert_data_access_role[] = $row;
                    }
                }
            }
            $res = $this->ugm->saveUserGroupDataAccess($data_type, $user_group_id, $insert_data_access_role);

            // end edited : arka.moharifrifai 2023-06-26 - save data feature

            /*
             * apply to other group
             * */
            if (isset($_POST['apply_function'])){
                $apply_function_to_group = $this->db->escapeString($this->input->getPost('apply_function'));
                foreach ($apply_function_to_group as $group_id){
                    $data2 = array_map(function($data) use ($group_id) {
                        $data['user_group_id'] = $group_id;
                        return $data;
                    }, $data2);
                    $this->ugm->updateUsergroupAuthNew($data2, $group_id);
                }
            }
            if (isset($_POST['apply_data_access'])){
                $apply_data_access_to_other_group = $this->db->escapeString($this->input->getPost('apply_data_access'));
                foreach ($apply_data_access_to_other_group as $group_id){

                    // user group data auth
                    $userGroupDataAuth['usergroup_id'] = $group_id;
                    $this->ugm->saveDataUserGroupDataAuth('edit', $userGroupDataAuth, $group_id);


                    // group data company access
                    $insert_data_company_access = array_map(function($data) use ($group_id) {
                        $data['usergroup_id'] = $group_id;
                        return $data;
                    }, $insert_data_company_access);
                    $this->ugm->saveUserGroupDataAccess('COMPANY', $group_id, $insert_data_company_access);

                    // group data area access
                    $insert_data_access_area = array_map(function($data) use ($group_id) {
                        $data['usergroup_id'] = $group_id;
                        return $data;
                    }, $insert_data_access_area);
                    $this->ugm->saveUserGroupDataAccess('AREA', $group_id, $insert_data_access_area);

                    // group data position access
                    $insert_data_position_access = array_map(function($data) use ($group_id) {
                        $data['usergroup_id'] = $group_id;
                        return $data;
                    }, $insert_data_position_access);
                    $this->ugm->saveUserGroupDataAccess('POSITION', $group_id, $insert_data_position_access);

                    // group data role access
                    $insert_data_access_role = array_map(function($data) use ($group_id) {
                        $data['usergroup_id'] = $group_id;
                        return $data;
                    }, $insert_data_access_role);
                    $this->ugm->saveUserGroupDataAccess('ROLE', $group_id, $insert_data_access_role);

                }
            }
            
            $this->session->setFlashdata('notif_user_group_success', '<strong>Berhasil.</strong> Grup Pengguna Berhasil diubah.');
            return redirect()->to('/usergroup');
        }
        
        if (isset($_POST['usergroup_id'])) {
            $usergroup_id = $this->db->escapeString($this->input->getPost('usergroup_id'));
            $function_id = $this->db->escapeString($this->input->getPost('function_id'));
            $items = $this->db->escapeString($this->input->getPost('items'));        

            $feature_id = explode(',', $items);
            
            $data2 = array();
            if (count($feature_id)>0){
                foreach($feature_id as $id){
                    if($id != ''){
                        $row = array(
							'user_group_id' => $usergroup_id
							, 'function_id' => $function_id
							, 'feature_id' => $id
							, 'created_by' => $this->db->escapeString($this->session->get(S_USER_NAME))
							, 'created_dt' => date('Y-m-d H:i:s')
							, 'changed_by' => $this->db->escapeString($this->session->get(S_USER_NAME))
							, 'changed_dt' => date('Y-m-d H:i:s')
						);
						$data2[] = $row;
                    }
                }
            }
            $res = $this->ugm->updateUsergroupAuth($data2, $usergroup_id, $function_id);

            if ($res > 0){
                $this->session->setFlashdata('notif_update_usergroup', '<strong>Berhasil.</strong> Fitur Berhasil diubah.');
            }else{
                $this->session->setFlashdata('notif_update_usergroup', '<strong>Gagal.</strong> Fitur gagal diubah.');
            }
            
            return redirect()->to(new URI(base_url() . 'usergroup/edit/' . $this->input->getPost('usergroup_id')));
        }
        
        $user_group_id = $this->db->escapeString($this->uri->setSilent()->getSegment(3));
        if ($user_group_id == '') {
            return redirect()->to('/usergroup');
        }
        $user_group = $this->ugm->getDataById($user_group_id);

        if (!$user_group->user_group_id){
            return redirect()->to('/usergroup');
        }

        $data['user_group'] = $user_group;
        $function_list = $this->ugm->getFunctionList();
        

        $fl = array();
        // edited : arka.moharifrifai 2023-06-26 - Get data feature
        $data_auth = $this->ugm->getDataAuth($user_group_id);
        if($data_auth == null){
            $userGroupDataAuth = array(
                'usergroup_id' => $user_group_id
                , 'related_area_flg' => '0'
                , 'related_position_flg' => '0'
                , 'related_role_flg' => '0'
                , 'subordinate_flg' => '0'
                , 'created_by' => $this->db->escapeString($this->session->get(S_USER_NAME))
                , 'created_dt' => date('Y-m-d H:i:s')
            );
            $this->ugm->saveDataUserGroupDataAuth('insert', $userGroupDataAuth);
            $data_auth = $this->ugm->getDataAuth($user_group_id);
        }
        $type = 'edit';
        for ($i = 0; $i<count($function_list); $i++) {
            $function_list[$i]->feature_list = $this->ugm->getFeatureList($function_list[$i]->function_id, $user_group_id, $type);
        } 
        $companyList = $this->ugm->getCompanyList();
        for ($i = 0; $i<count($companyList); $i++) {
            $work_unit = $this->ugm->getWorkUnitList($companyList[$i]->company_id);
            for($j = 0; $j<count($work_unit); $j++){
                $work_unit[$j]->name = htmlspecialchars($work_unit[$j]->name);
            }
            $companyList[$i]->work_unit = $work_unit;

            $position = $this->ugm->getPositionList($companyList[$i]->company_id);
            for($j = 0; $j<count($position); $j++){
                $position[$j]->position_name = htmlspecialchars($position[$j]->position_name);
            }
            $companyList[$i]->position = $position;
            
            $role = $this->ugm->getRoleList($companyList[$i]->company_id);
            for($j = 0; $j<count($role); $j++){
                $role[$j]->role_name = htmlspecialchars($role[$j]->role_name);
            }
            $companyList[$i]->role = $role;
        } 
        $data['company_list'] = $companyList;
        $data['data_auth'] = $data_auth;
        $data['data_access_type'] = $this->ugm->getSystem('data_access_type', '');  
        
        $getDataAccess = $this->ugm->getDataAccess($user_group_id, 'COMPANY');
        $data_access_company = array();
        foreach($getDataAccess as $row){
            $data_access_company[] = $row->data_id;
        }
        $data['data_access_company'] = $data_access_company;

        $getDataAccess = $this->ugm->getDataAccess($user_group_id, 'AREA');
        $data_access_area = array();
        foreach($getDataAccess as $row){
            $data_access_area[] = $row->data_id;
        }
        $data['data_access_area'] = $data_access_area;

        $getDataAccess = $this->ugm->getDataAccess($user_group_id, 'POSITION');
        $data_access_position = array();
        foreach($getDataAccess as $row){
            $data_access_position[] = $row->data_id;
        }
        $data['data_access_position'] = $data_access_position;

        $getDataAccess = $this->ugm->getDataAccess($user_group_id, 'ROLE');
        $data_access_role = array();
        foreach($getDataAccess as $row){
            $data_access_role[] = $row->data_id;
        }
        $data['data_access_role'] = $data_access_role;
        $data['count'] = $this->ugm->get_used_user_count($user_group_id);
        // end edited : arka.moharifrifai 2023-06-26 - Get data feature
        $data['function_list'] = $function_list;
        $data['jsapp'] = array('user_group_edit');
        $data['user_groups'] = $this->ugm->getUserGroup(array($user_group_id));

        return view('usergroup/user_group_edit', $data);
    }
    
    function reject(){
        echo "hello world";
    }

}
