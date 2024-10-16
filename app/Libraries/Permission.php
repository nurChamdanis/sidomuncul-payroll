<?php

namespace App\Libraries;

use CodeIgniter\HTTP\Response;

class Permission {
    protected $session;
    protected $CI;// CI instance
    protected $CID;
    protected $UgroupID;
    protected $isExpired;
    protected $page;
    protected $_page;
    protected $action;
    protected $stdAction = array("create", "id", "edit", "delete", "approve", "req_change");
    protected $blockAction = array("create", "edit", "delete", "approve", "req_change"); //block action ketika masa percobaan sudah expired
    protected $AffiliateID;
    protected $db;
    protected $response;
    protected $uri;

    function __construct() {
        $this->uri = service('uri');

        $this->session = session();
        $this->db = \Config\Database::connect();

        // set user_group and company id from session
        $this->UgroupID     = ($this->session->get(S_USER_GROUP_ID)) ? $this->session->get(S_USER_GROUP_ID) : 0;
        $this->CID          = ($this->session->get(S_COMPANY_ID)) ? $this->session->get(S_COMPANY_ID) : 0;
        $this->isExpired    = ($this->session->get(S_IS_EXPIRED)) ? $this->session->get(S_IS_EXPIRED) : 0;
        $this->page         = $this->uri->setSilent()->getSegment(1);
        $this->action       = $this->uri->setSilent()->getSegment(2);
		// update @moharifrifai 2022-12-23
        $this->AffiliateID     = ($this->session->get(S_AFFILIATE_ID)) ? $this->session->get(S_AFFILIATE_ID) : 0;
        
        if (in_array($this->action, $this->stdAction)){
            $this->_page = $this->page.'/'.$this->action;
        }else{
            $this->_page = $this->page;
        }
    }

    function redirectIfNotLogin(){
        $session = session();
        $uri     = service('uri');
       
        // set user_group and company id from session
        $UgroupID     = ($session->get(S_USER_GROUP_ID)) ? $session->get(S_USER_GROUP_ID) : 0;
        $CanAccessApp = ($session->get(S_CAN_ACCESS_PAYROLL_APP)) ? $session->get(S_CAN_ACCESS_PAYROLL_APP) : 0;
        $CID          = ($session->get(S_COMPANY_ID)) ? $session->get(S_COMPANY_ID) : 0;
        $isExpired    = ($session->get(S_IS_EXPIRED)) ? $session->get(S_IS_EXPIRED) : 0;
        $AffiliateID  = ($session->get(S_AFFILIATE_ID)) ? $session->get(S_AFFILIATE_ID) : 0;
        $page         = ($uri->setSilent()->getSegment(1)) ? $uri->setSilent()->getSegment(1) : "";
        $action       = ($uri->setSilent()->getSegment(2)) ? $uri->setSilent()->getSegment(2) : "";

        if ($isExpired == 1){ // jika sudah kadaluarsa masa percobaannya, check actionnya
            if (in_array($action, $this->blockAction)){
                return redirect()->to($session->get(S_DEFAULT_LANDING));
            }
        }
        
        if (($CanAccessApp == null || $CanAccessApp == 0) && ($UgroupID == null || $UgroupID == 0)) {
            $session->setFlashdata('redirect_uri', current_url());
            return redirect()->to('login');
        }

        if (($CID == null || $CID == 0) && ($AffiliateID == null || $AffiliateID == 0)) {
            $session->setFlashdata('redirect_uri', current_url());
            return redirect()->to('login');
        }
    }
    
    function get_user_permissions()
    {        
		$builder = $this->db->table('tb_m_function f');
        $builder->select('ff.feature_id, ff.feature_name, ff.feature_action');
        $builder->join('tb_m_user_group_payroll_auth uga', 'f.function_id = uga.function_id');
        $builder->join('tb_m_function_feature_payroll ff', 'f.function_id = ff.function_id and uga.feature_id = ff.feature_id');
        $builder->where('f.function_active', 1);
        $builder->where('ff.feature_element_type', 'page');
        $builder->where('ff.feature_action', $this->_page);
        $query_1 = $builder->getCompiledSelect();

        $query_2 = "";
        if ($this->AffiliateID != null && $this->AffiliateID != 0) {
            $builder = $this->db->table('tb_m_function_payroll f');
            $builder->select('ff.feature_id, ff.feature_name, ff.feature_action');
            $builder->join('tb_m_function_feature_payroll ff', 'f.function_id = ff.function_id');
            $builder->where('f.function_active', 1);
            $builder->where('ff.feature_element_type', 'page');
            $builder->where('ff.feature_action', $this->_page);
            $query_2 = $builder->getCompiledSelect();
            $query_2 = ' UNION ' . $query_2;
        }

        $builder = $this->db->table('tb_m_function_payroll f');
        $builder->select('ff.feature_id, ff.feature_name, ff.feature_action');
        $builder->join('tb_m_function_feature_payroll ff', 'f.function_id = ff.function_id');
        $builder->where('f.function_active', 1);
        $builder->where('ff.feature_element_type', 'page');
        $builder->where('ff.feature_action', $this->_page);
        $query_3 = $builder->getCompiledSelect();
        $query_3 = ' UNION ' . $query_3;

        $query = $this->db->query($query_1 . $query_2 . $query_3);
        
        if ($query->getNumRows() > 0) {
            $data = $query->getResultArray();
            return $data;
        } else {
            return false;
        }
    }
    function get_access_button()
    {
		// update @moharifrifai 2022-12-23
        if ($this->isExpired == 1){ // jika sudah kadaluarsa masa percobaannya, ga melakukan generate button
            return false;
        }
        
        $where = "(ff.feature_element_type LIKE '%button%' OR ff.feature_element_type = 'submit')";

        $builder = $this->db->table('tb_m_function_payroll f');
        $builder->select("ff.feature_element_id, ff.feature_element_class, 
            CASE WHEN '".get_cookie('lang_code',true)."' = 'ID' THEN COALESCE(ff.feature_name_id, ff.feature_name) ELSE ff.feature_name END AS feature_name, 
            ff.feature_element_icon, ff.feature_position, ff.feature_action, ff.feature_element_type");
        $builder->join('tb_m_user_group_payroll_auth uga', 'f.function_id = uga.function_id');
        $builder->join('tb_m_function_feature_payroll ff', 'f.function_id = ff.function_id and uga.feature_id = ff.feature_id');
        $builder->orWhere($where);
        $builder->where('f.function_controller', $this->page);
        $builder->where('f.function_active', 1);
        $query_1 = $builder->getCompiledSelect();

        $query_2 = "";
        if ($this->AffiliateID != null && $this->AffiliateID != 0) {
            $builder = $this->db->table('tb_m_function_payroll f');
            $builder->select("ff.feature_element_id, ff.feature_element_class, 
                CASE WHEN '".get_cookie('lang_code',true)."' = 'ID' THEN COALESCE(ff.feature_name_id, ff.feature_name) ELSE ff.feature_name END AS feature_name, 
                ff.feature_element_icon, ff.feature_position, ff.feature_action, ff.feature_element_type");
            $builder->join('tb_m_function_feature_payroll ff', 'f.function_id = ff.function_id');
            $builder->orWhere($where);
            $builder->whereIn('f.function_id', [9, 901, 902, 903]);
            $builder->where('f.function_active', 1);
            $builder->where('f.function_controller', $this->page);
            $query_2 = $builder->getCompiledSelect();
            $query_2 = ' UNION ' . $query_2;
        }

        $builder = $this->db->table('tb_m_function_payroll f');
        $builder->select("ff.feature_element_id, ff.feature_element_class, 
            CASE WHEN '".get_cookie('lang_code',true)."' = 'ID' THEN COALESCE(ff.feature_name_id, ff.feature_name) ELSE ff.feature_name END AS feature_name, 
            ff.feature_element_icon, ff.feature_position, ff.feature_action, ff.feature_element_type");
        $builder->join('tb_m_function_feature_payroll ff', 'f.function_id = ff.function_id');
        $builder->orWhere($where);
        $builder->whereIn('f.function_id', [904]);
        $builder->where('f.function_active', 1);
        $builder->where('f.function_controller', $this->page);
        $query_3 = $builder->getCompiledSelect();
        $query_3 = ' UNION ' . $query_3;

        $query = $this->db->query($query_1 . $query_2 . $query_3);

        if ($query->getNumRows() > 0) {
            $data = array();
            foreach ( $query->getResultArray() as $row)
            {
                if ( $row['feature_element_type']=="button_group" || $row["feature_element_type"]== "button_group_list"){
                    $r = array(
                        'name'          => $row['feature_element_id'],
                        'id'            => $row['feature_element_id'],
                        'type'          => $row['feature_element_type'],
                        'content'       => $row['feature_name'],
                        'class'         => $row['feature_element_class'],
                        'onclick'       => $row['feature_action']
                    );
                }else if ($row['feature_element_type']=="button" || $row['feature_element_type'] == "submit"){
                    $r = array(
                        'name'          => $row['feature_element_id'],
                        'id'            => $row['feature_element_id'],
                        'type'          => $row['feature_element_type'],
                        'content'       => '<i class="'.$row['feature_element_icon'].'"> </i> '.$row['feature_name'],
                        'class'         => $row['feature_element_class'],
                        'onclick'       => $row['feature_action']
                    );
                }
                
                $data[ $row['feature_element_id'] ] = $r;
            }
            return $data;
        }
        else
        {
            return false;
        }
    }
    
    
    function get_access_menu()
    {
        // echo get_cookie('lang_code',true);
        // die;
        // sql = "SELECT
        //                ff.function_parent AS function_grp,
        //                f.function_name AS function_grp_nm,
        //                ff.function_id AS function_id,
        //                uga.user_group_id AS user_group_id,
        //                ff.function_name AS function_name,
        //                ff.function_controller AS function_controller,
        //                ff.function_active AS function_active,
        //                ff.function_order AS function_order,
        //                f.function_icon,
		// 				ff.function_link_visible
        //        FROM
        //                tb_m_function f
        //                JOIN tb_m_function ff ON f.function_id = ff.function_parent 
        //                JOIN tb_m_company_function cf ON f.function_id = cf.function_id and cf.company_id = '".$this->CID."'
        //                JOIN tb_m_user_group_payroll_auth uga ON ff.function_id = uga.function_id
        //        where f.function_active = 1 and user_group_id = '".$this->UgroupID."'
        //        GROUP BY
        //                uga.function_id,
        //                uga.user_group_id";
		
		// update @moharifrifai 2022-12-23
		$sql = "
			select 
				ff.function_id as function_grp
				, CASE WHEN '".get_cookie('lang_code',true)."' = 'ID' THEN COALESCE(ff.function_name_id, ff.function_name) ELSE ff.function_name END AS function_grp_nm
				, ff.function_icon
                , ff.function_order as function_order_1
				, f.function_id
				, CASE WHEN '".get_cookie('lang_code',true)."' = 'ID' THEN COALESCE(f.function_name_id, f.function_name) ELSE f.function_name END AS function_name
				, f.function_controller
				, f.function_link_visible
                , f.function_order as function_order_2
			from 
				tb_m_user_group_payroll_auth uga 
				inner join tb_m_user_group_payroll ug on ug.user_group_id = uga.user_group_id
				inner join tb_m_function_payroll f on f.function_id = uga.function_id
				inner join tb_m_function_payroll ff on ff.function_id = f.function_parent
			where
				-- ug.company_id = '" . $this->CID . "' 
                ug.user_group_id = '" . $this->UgroupID . "' and f.function_active = '1'
                AND ff.function_active = '1' 
			group by
				uga.function_id
		";
        // if(($this->AffiliateID != null && $this->AffiliateID != 0)){
        //     $sql .= "
        //         UNION
        //         select 
        //             ff.function_id as function_grp
        //             , ff.function_name as function_grp_nm
        //             , ff.function_icon
        //             , ff.function_order as function_order_1
        //             , f.function_id
        //             , f.function_name
        //             , f.function_controller
        //             , f.function_link_visible
        //             , f.function_order as function_order_2
        //         from tb_m_function f
        //             inner join tb_m_function ff on ff.function_id = f.function_parent
        //         where
        //             f.function_id in (901,902,903) and f.function_active = '1'
        //         group by
        //             f.function_id
        //     ";
        // }
        // $sql .= "
        //     UNION
        //     select 
        //         ff.function_id as function_grp
        //         , ff.function_name as function_grp_nm
        //         , ff.function_icon
        //         , ff.function_order as function_order_1
        //         , f.function_id
        //         , f.function_name
        //         , f.function_controller
        //         , f.function_link_visible
        //         , f.function_order as function_order_2
        //     from tb_m_function f
        //         inner join tb_m_function ff on ff.function_id = f.function_parent
        //     where
        //         f.function_id in (904) and f.function_active = '1'
        //     group by
        //         f.function_id
        // ";
		$sql .= "
            order by
                function_order_1, function_order_2
		";
            
        $query = $this->db->query($sql);

        if ($query->getNumRows())
        {
            return $query->getResultArray();
        }
        else
        {
            return false;
        }
    }
}
