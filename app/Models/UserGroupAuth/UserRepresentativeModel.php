<?php

namespace App\Models\UserGroupAuth;

use App\Models\BaseModel;

class UserRepresentativeModel extends BaseModel
{
    public $per_page = 10;
    public $S_COMPANY_ID = '';
    public $S_ACCESS_COMPANY_ID = '';
    public $S_ACCESS_AREA_ID = '';
    public $S_EMPLOYEE_ID = '';
    public $S_ACCESS_POSITION_ID = '';
    public $S_ACCESS_ROLE_ID = '';
    public $S_IS_ADMIN = '';

    function __construct() {
        parent:: __construct();

        $this->S_COMPANY_ID = $this->session->get(S_COMPANY_ID);
        $this->S_ACCESS_COMPANY_ID = $this->session->get(S_ACCESS_COMPANY_ID);
        $this->S_ACCESS_AREA_ID = $this->session->get(S_ACCESS_AREA_ID);
        $this->S_EMPLOYEE_ID = $this->session->get(S_EMPLOYEE_ID);
        $this->S_ACCESS_ROLE_ID = $this->session->get(S_ACCESS_ROLE_ID);
        $this->S_ACCESS_POSITION_ID = $this->session->get(S_ACCESS_POSITION_ID);
        $this->S_IS_ADMIN = $this->session->get(S_IS_ADMIN);
    }

    function get_list_of_function_id($only = []){
        $fields = [
            'function_id',
            'function_name',
            'function_name_id'
        ];
        $query = $this->db->table('tb_m_function_payroll')->select($fields)
            ->where('function_link_visible', '1')
            ->whereIn('function_id', $only)
            ->orderBy('function_id', 'asc');
        return $query->get()->getResult();
    }



    function employee($options = array()) {
        $page = array_key_exists('page', $options) ? $options['page'] : 1;
        $search = array_key_exists('search', $options) ? $options['search'] : '';
        $company_id = array_key_exists('company_id', $options) ? $options['company_id'] : '';
        $work_unit_id = array_key_exists('work_unit_id', $options) ? $options['work_unit_id'] : '';
        $exclude_employee_id = array_key_exists('exclude_employee_id', $options) ? $options['exclude_employee_id'] : '';

        $filters = array("company_id" => $company_id, "work_unit_id" => $work_unit_id, "exclude_employee_id" => $exclude_employee_id);

        $results = $this->employee_query($filters, $page, $search)->get()->getResult();
        $results_count = $this->employee_query($filters, $page, $search, false)->countAllResults();

        return [
            'results' => $this->map_options(array(
//                'placeholder' => lang('all_employee'),
                'fields' => array('no_reg','employee_name', 'employee_id'),
                'data' => $results,
                'page' => $page,
                'search' => $search,
                'selected' => 'yoga@arkamaya.co.id'
            )),
            'pagination' => [
                'more' => ($page * $this->per_page) < $results_count
            ],
        ];
    }

    function saveRequestType($data, $email){
        $builder = $this->db->table('tb_m_users_request_type');
        $builder->where('user_email', $email);
        $builder->delete();
        
        if(count($data) > 0)
        $builder->insertBatch($data);
        if ($this->db->affectedRows() > 0) {
            return true;
        }
    }

    function getRequestTypeByUser($email, $function_id = null){
        $query = $this->db->table('tb_m_users_request_type')->select(['request_type_id', 'tb_m_function_payroll.function_name', 'tb_m_function_payroll.function_name_id'])
            ->where('user_email', $email)
            ->join('tb_m_function_payroll', 'tb_m_function_payroll.function_id = tb_m_users_request_type.request_type_id');
        if (!is_null($function_id)){
            $query = $query->where('request_type_id', $function_id);
        }
        return $query->get()->getResult();
    }

    function getEmployeeRepresentative($email){
        $query = $this->db->table('tb_m_users_approver_delegation')->select([
            'employee_name',
            'tb_m_employee.employee_id',
            'tb_m_employee.user_email',
            'tb_m_employee.no_reg'
        ])->where('tb_m_users_approver_delegation.user_email', $email)
            ->join('tb_m_employee', 'tb_m_employee.employee_id = tb_m_users_approver_delegation.employee_id');
        return $query->get()->getResult();
    }

    function saveDelegation($data, $email){
        $builder = $this->db->table('tb_m_users_approver_delegation');
        $builder->where('user_email', $email);
        $builder->delete();

        if(count($data) > 0)
            $builder->insertBatch($data);

        if ($this->db->affectedRows() > 0) {
            return true;
        }
    }

    function employee_query($params = array(), $page = 1, $search = '', $limit = true)
    {
        $offset = $page * $this->per_page - $this->per_page;
        $company_id = array_key_exists("company_id", $params) ? $params['company_id'] : '';
        $work_unit_id = array_key_exists("work_unit_id", $params) ? $params['work_unit_id'] : '';
        $exclude_employee_id = array_key_exists("exclude_employee_id", $params) ? $params['exclude_employee_id'] : '';

        $query = $this->db;
        $query = $query->table('tb_m_employee')->select('employee_id,employee_name,no_reg');
        $query = $query->where('is_active', '1');
        $query = $query->where('user_email IS NOT NULL');

        if(!empty($search)){
            $query = $query->where('(employee_name like "%'.$search.'%" or 
            no_reg like "%'.$search.'%"
            )');
        }

        $workunitid = $this->db->query('SELECT work_unit_id FROM tb_m_employee WHERE employee_id = ?', $this->db->escapeString($this->S_EMPLOYEE_ID))->getRow();

        if($this->S_IS_ADMIN){
            if(!empty($this->S_ACCESS_POSITION_ID)){
                $query = $query->where('position_id IN ('.$this->S_ACCESS_POSITION_ID.')');
            }

            if(!empty($this->S_ACCESS_ROLE_ID)){
                $query = $query->where('role_id IN ('.$this->S_ACCESS_ROLE_ID.')');
            }

            if(!empty($this->S_ACCESS_COMPANY_ID)){
                $query = $query->where('company_id IN ('.$this->S_ACCESS_COMPANY_ID.')');
            } else {
                $query = $query->where('company_id IN ('.$this->S_COMPANY_ID.')');
            }

            if(!empty($this->S_ACCESS_AREA_ID)){
                $query = $query->where('work_unit_id IN ('.$this->S_ACCESS_AREA_ID.')');
            } else {
                $query = $query->where('work_unit_id IN ('.$workunitid->work_unit_id.')');
            }
        } else {
            $where = '(employee_id = ' . $this->S_EMPLOYEE_ID . ' or (atasan_id = '.$this->S_EMPLOYEE_ID.' or atasan2_id = '.$this->S_EMPLOYEE_ID.'))';
            $query = $query->where($where);
        }

        if($company_id <> '-' && !empty($company_id)){
            $query = $query->where('company_id', $company_id);
        }

        if($work_unit_id <> '-' && !empty($work_unit_id)){
            $query = $query->where('work_unit_id', $work_unit_id);
        }

        if($exclude_employee_id <> '-' && !empty($exclude_employee_id)){
            $query = $query->whereNotIn('employee_id', explode(",", $exclude_employee_id));
        }

        if($limit){
            $query = $query->limit($this->per_page, $offset);
        }

        return $query;
    }

    function map_options($options = array())
    {
        $results = array_key_exists("data",$options) ? $options["data"] : array();
        $fields = array_key_exists("fields",$options) ? $options["fields"] : array();
        $page = array_key_exists("page",$options) ? $options["page"] : 1;
        $search = array_key_exists("search",$options) ? $options["search"] : '';
        $placeholder = array_key_exists("placeholder",$options) ? $options["placeholder"] : '';

        $data = [];
        if($page == 1 && empty($search)){
            $data[0] = ['id' => '-','text' => $placeholder];
        }

        if(!empty($results)){
            foreach($results as $r)
            {
                $option = [
                    'id' => $r->{$fields[2]},
                    'text' => (array_key_exists(2, $fields) ? strtoupper($r->{$fields[0]}) . ' - ' : '') . $r->{$fields[1]}];
                if(!empty($selected_id)){
                    if($selected_id == $r->{$fields[0]}){
                        $option['selected'] = true;
                    }
                }
                $data[] = $option;
            }
        }

        return $data;
    }

    function deleteRequestType($email){
        $builder = $this->db->table('tb_m_users_request_type');
        $builder->where('user_email', $email);
        $builder->delete();
    }

    function deleteDelegation($email){
        $builder = $this->db->table('tb_m_users_approver_delegation');
        $builder->where('user_email', $email);
        $builder->delete();
    }
}