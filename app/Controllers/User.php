<?php

namespace App\Controllers;

/**
 * @author misbah@arkamaya.co.id
 * @since 2017-02-06 
 * --------------------------------
 * @modified luthfi.aziz@arkamaya.co.id on May 2024
 */

use App\Controllers\MyController;
use App\Core\MyController as CoreMyController;
use App\Models\Shared\SharedModel;
use App\Models\UserGroupAuth\UserGroupModel;
use App\Models\UserGroupAuth\UserModel;
use App\Models\UserGroupAuth\UserRepresentativeModel;
use CodeIgniter\HTTP\URI;

class User extends CoreMyController
{
    protected $shared_model;
    protected $um;
    protected $usergroup_model;
    protected $urm;

    protected $config_users_default = 0;
    protected $config_users_addons = 0;
    protected $config_users_total = 0;

    function __construct()
    {
        parent::__construct();

        $this->shared_model = new SharedModel();
        $this->um = new UserModel();
        $this->usergroup_model = new UserGroupModel();
        $this->urm = new UserRepresentativeModel();

        $this->config_users_default = $this->shared_model->get_company_prefferences("config_users", "default")->system_value_num;
        $this->config_users_addons = $this->shared_model->get_company_prefferences("config_users", "addons")->system_value_num;
        //$this->config_users_total = $this->config_users_default + $this->config_users_addons;

    }

    function index()
    {
        $data = array_merge($this->data, get_title($this->menu, 'user'));
        $data['jsapp'] = array('user/user');

        // $data['default_users'] = $this->um->get_usercount();
        // $data['users_total'] = $this->um->countData();

        // coding ori
        // $data['default_users'] = $this->config_users_total;

        // coding edited by arka.budi, 01/03/2019
        $data['default_users'] = $this->um->countData_emplo();
        $data['users_total'] = $this->um->countData();
        $data['listCompany'] = $this->shared_model->getListCompanyActive();

        $data['stitle'] = 'Pengguna';
        return view('user/user', $data);
    }

    function getData()
    {
        $order = !empty($this->input->getPost('order')) ? $this->db->escapeString($this->input->getPost('order')) : '';
        $column = $this->input->getPost('columns');
        // $idx_cols = $order[0]['column'];

        $def = array(
            'draw' => $this->input->getPost('draw'),
            'length' => $this->db->escapeString($this->input->getPost('length')),
            'start' => $this->db->escapeString($this->input->getPost('start')),
        );

        $start = isset($_POST['start']) ? intval($this->db->escapeString($_POST['start'])) : 0;
        $length = isset($_POST['length']) ? intval($this->db->escapeString($_POST['length'])) : 50;
        $keyword = !empty($this->input->getPost('search')['value']) ? $this->db->escapeString($this->input->getPost('search')['value'], true) : '';
        $company_id = isset($_POST['company_id']) ? intval($_POST['company_id']) : null;
        $user_group_id = isset($_POST['user_group_id']) ? intval($_POST['user_group_id']) : null;

        $results = $this->um->getData($start, $length, $keyword, $company_id, $user_group_id);
        $recordsTotal = $this->um->countData($keyword, $company_id, $user_group_id);

        $data = array();
        foreach ($results as $r) {
            $row = array();
            $row[] = htmlspecialchars($r->company_name);
            $row[] = '<a href="' . site_url('user/id/' . md5(htmlspecialchars($r->user_email))) . '" title="Lihat Detil Pengguna">' . htmlspecialchars($r->user_email) . '</a>';
            $row[] = (htmlspecialchars($r->employee_name) != '') ? htmlspecialchars($r->employee_name) : '-';
            $row[] = htmlspecialchars($r->user_group_description);
            $row[] = htmlspecialchars($r->is_active_text);
            $row[] = (htmlspecialchars($r->user_last_logged_in) != '') ? date('d F Y H:i:s', strtotime(htmlspecialchars($r->user_last_logged_in))) : 'Belum Pernah';
            $row[] = htmlspecialchars($r->email_confirmed_text);
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

    function check_email()
    {
        $user_email = $this->db->escapeString($this->input->getPost('user_email'));
        $res = $this->um->check_email($user_email);
        echo $res;
    }

    function create()
    {
        $default_users = $this->config_users_total; // $this->um->get_usercount();
        $users_total = $this->um->countData();
        // edited by arka.budi, 01/03/2019
        // if ($users_total >= $default_users)
        // {
        // 	$this->session->setFlashData('notif_user_failed', '<strong>Gagal.</strong> Anda telah mencapai Batas Maksimum Jumlah Pengguna. Perlu Tambahan? <a href="'.site_url('setting').'">Klik disini</a>');
        // 	redirect('user');
        // }

        $data = array_merge($this->data, get_title($this->menu, 'user'));
        if ($_POST) {
            $digits = 50;
            $arrdata = array(
                // 'company_id' => $this->db->escapeString($this->session->get(S_COMPANY_ID)), 
                'user_email' => $this->db->escapeString($this->input->getPost('user_email'))
                // , 'user_password' => md5($this->input->getPost('user_password'))
                , 'full_name' => $this->db->escapeString($this->input->getPost('full_name')), 'user_group_id' => $this->db->escapeString($this->input->getPost('user_group_id')), 'is_active' => $this->db->escapeString($this->input->getPost('is_active')), 'super_admin' => 0, 'email_confirmed_code' => md5(random_string('alnum', 8)) // md5(rand(pow(10, $digits-1), pow(10, $digits)-1))
                , 'created_by' => $this->db->escapeString($this->session->get(S_USER_NAME)), 'created_dt' => date('Y-m-d H:i:s'), 'employee_id' => ($this->input->getPost('employee_id') == '0') ? null : $this->db->escapeString($this->input->getPost('employee_id'))
            );

            $insert = $this->um->saveData('insert', $arrdata);
            if ($insert) {

                // update employee jika pegawai diset
                if ($this->db->escapeString($this->input->getPost('employee_id')) != '0') {
                    $this->db->query("update tb_m_employee set 
						user_email = '" . $this->db->escapeString($this->input->getPost('user_email')) . "'
						, work_email = '" . $this->db->escapeString($this->input->getPost('user_email')) . "'
						where 
							employee_id = '" . $this->db->escapeString($this->input->getPost('employee_id')) . "'
					");
                }


                $this->send_mail_confirm($arrdata['user_email'], $this->db->escapeString($this->input->getPost('user_password')));
                $this->session->setFlashData('notif_user_success', '<strong>Berhasil.</strong> Pengguna Berhasil diundang.');
            } else {
                $this->session->setFlashData('notif_user_failed', '<strong>Gagal.</strong> Pengguna Gagal dibuat.');
            }
            redirect('user/id/' . md5($this->input->getPost('user_email')));
        }

        $data['listCompany'] = $this->shared_model->getListCompanyActive();
        $data['jsapp'] = array('user/user_create');
        //$data['user_group'] = $this->um->get_user_group();
        // $data['user_group'] = $this->usergroup_model->get_user_groups('','','user_group_id desc');
        $data['user_group'] = array();
        $data['employee_assignee'] = $this->um->get_employee_to_assign();

        return view('user/user_create', $data);
    }

    function id()
    {
        $user_email = $this->db->escapeString($this->uri->setSilent()->getSegment(3));
        if ($user_email == '') {
            redirect('user');
        }

        $user = $this->um->getDataById($user_email);
        if (!$user) {
            redirect('user');
        }

        if (isset($_POST['deleted'])) {
            $deleted = $this->um->delete($this->db->escapeString($_POST['deleted']));
            $this->urm->deleteRequestType($user->user_email);
            $this->urm->deleteDelegation($user->user_email);
            if ($deleted) {
                $this->session->setFlashData('notif_user_success', '<strong>Berhasil.</strong> Pengguna Berhasil dihapus.');
            } else {
                $this->session->setFlashData('notif_user_failed', '<strong>Gagal. </strong> Pengguna Gagal dihapus.');
            }
            redirect('user');
        }

        $data = array_merge($this->data, get_title($this->menu, 'user'));
        $data['listCompany'] = $this->shared_model->getListCompanyActive();
        $data['user'] = $user;
        $data['user_group'] = $this->um->get_user_group();
        $data['request_types'] = $this->urm->getRequestTypeByUser($user->user_email);
        $data['list_employee_represntative'] = $this->urm->getEmployeeRepresentative($user->user_email);
        
        return view('user/user_id', $data);
    }

    function edit()
    {
        $data = array_merge($this->data, get_title($this->menu, 'user'));
        if ($_POST) {
            $arrdata = array(
                'user_email' => $this->db->escapeString($this->input->getPost('user_email')), 'full_name' => $this->db->escapeString($this->input->getPost('full_name')), 'user_group_id' => $this->db->escapeString($this->input->getPost('user_group_id')), 'is_active' => $this->db->escapeString($this->input->getPost('is_active')), 'changed_by' => $this->db->escapeString($this->session->get(S_USER_NAME)), 'changed_dt' => date('Y-m-d H:i:s'), 'employee_id' => ($this->input->getPost('employee_id') == '0') ? null : $this->db->escapeString($this->input->getPost('employee_id'))
            );
            if ($this->db->escapeString($this->input->getPost('password')) != '') {
                $arrdata['user_password'] = md5($this->db->escapeString($this->input->getPost('password')));
            }
            $update = $this->um->saveData('update', $arrdata, $arrdata['user_email']);
            $function_id = !empty($this->input->getPost('function_id')) ? $this->input->getPost('function_id') : array();
            $dataReqType = [];

            if (count($function_id) > 0) {

                foreach ($function_id as $key => $value) {
                    $dataReqType[] = [
                        'request_type_id' => $value,
                        'user_email' => $arrdata['user_email'],
                        'approval_flg' => 1,
                        'created_by' => $this->db->escapeString($this->session->get(S_USER_NAME)),
                        'created_dt' => date('Y-m-d H:i:s'),
                        'changed_by' => $this->db->escapeString($this->session->get(S_USER_NAME)),
                        'changed_dt' => date('Y-m-d H:i:s')
                    ];
                }
            }

            $insertRequestType = $this->urm->saveRequestType($dataReqType, $arrdata['user_email']);
            $delegation = !empty($this->input->getPost('employee_delegation')) ? $this->input->getPost('employee_delegation') : array();
            $dataDelegation = [];

            if (count($delegation) > 0) {
                foreach ($delegation as $key => $value) {
                    $dataDelegation[] = [
                        'user_email' => $arrdata['user_email'],
                        'employee_id' => $value,
                        'created_by' => $this->db->escapeString($this->session->get(S_USER_NAME)),
                        'created_dt' => date('Y-m-d H:i:s'),
                        'changed_by' => $this->db->escapeString($this->session->get(S_USER_NAME)),
                        'changed_dt' => date('Y-m-d H:i:s')
                    ];
                }
            }
            $insertDelegation = $this->urm->saveDelegation($dataDelegation, $arrdata['user_email']);
            if ($update || $insertRequestType || $insertDelegation) {

                // 	// update employee jika pegawai diset
                // 	if ($this->input->getPost('employee_id') != '0')
                // 	{
                // 		$this->db->query("update tb_m_employee set 
                // 				user_email = null
                // 				, work_email = null
                // 				where 
                // 					employee_id = '" . $this->db->escapeString($this->input->getPost('xemployee_id')) . "'
                // 			");

                // 		$this->db->query("update tb_m_employee set 
                // 			user_email = '" . $this->db->escapeString($this->input->getPost('user_email')) . "'
                // 			, work_email = '" . $this->db->escapeString($this->input->getPost('user_email')) . "'
                // 			where 
                // 				employee_id = '" . $this->db->escapeString($this->input->getPost('employee_id')) . "'
                // 		");
                // 	}
                // 	else
                // 	{
                // 		if ($this->input->getPost('xemployee_id') != '')
                // 		{
                // 			$this->db->query("update tb_m_employee set 
                // 				user_email = null
                // 				, work_email = null
                // 				where 
                // 					employee_id = '" . $this->db->escapeString($this->input->getPost('xemployee_id')) . "'
                // 			");

                // 			// update user reset
                // 			$this->db->query("
                // 				update 
                // 					tb_m_users 
                // 				set 
                // 					user_password = null
                // 					, user_photo = null
                // 					, full_name = null
                // 					, user_last_logged_in = null
                // 					, email_confirmed = '0'
                // 					, email_confirmed_code = null
                // 					, reset_password_code = null
                // 					, employee_id = null
                // 					, changed_by = '" . $this->db->escapeString($this->session->get(S_USER_NAME)) . "'
                // 				where
                // 					employee_id = '" . $this->db->escapeString($this->input->getPost('xemployee_id')) . "'
                // 			");
                // 		}
                // 	}

                // no need
                // $check_confirm = $this->um->getDataById(md5($arrdata['user_email']));
                // if ($check_confirm) {
                // 	if ($check_confirm->email_confirmed == 0) {
                // 		$this->send_mail_confirm($arrdata['user_email']);
                // 	}
                // }
                $this->session->setFlashData('notif_user_success', '<strong>Berhasil.</strong> Pengguna Berhasil diubah.');
            } else {
                $this->session->setFlashData('notif_user_failed', '<strong>Gagal.</strong> Pengguna Gagal diubah.');
            }

            // Redirect to the full URL
            return redirect()->to(new URI(base_url() . 'user/id/' . md5($this->input->getPost('user_email'))));
        }

        $user_email = $this->db->escapeString($this->uri->setSilent()->getSegment(3));
        if ($user_email == '') {
            redirect('user/user');
        }

        $user = $this->um->getDataById($user_email);
        if (!$user) {
            redirect('user/user');
        }

        $data['jsapp'] = array('shared/data_options', 'user/user_edit');
        $data['user_group'] = $this->um->get_user_group_by_company_id($user->company_id);
        $data['user'] = $user;
        $data['employee_assignee'] = $this->um->get_employee_to_assign();
        $data['listCompany'] = $this->shared_model->getListCompanyActive();

        $only = [
            '201', // offical travel
            '202', // reimburse
            '203', // leave
            '204', // overtime
            '207', // other request
            '208', // dws change request
            '210', // approval clock in/out
            '212', // approval cancellations
            '602', // proposed training
        ];
        $data['list_of_function_id'] = $this->urm->get_list_of_function_id($only);

        $functions = $this->urm->getRequestTypeByUser($user->user_email);
        $selectedFunction = [];
        if (!empty($functions)) {
            $selectedFunction = array_map(function ($item) {
                return $item->request_type_id;
            }, $functions);
        }
        $data['selected_function_id'] = $selectedFunction;
        $data['list_employee_represntative'] = $this->urm->getEmployeeRepresentative($user->user_email);

        return view('user/user_edit', $data);
    }

    function getEmployeeByAccess()
    {
        header('Content-Type: application/json');

        $employee = $this->urm->employee(
            array(
                'page' => $this->input->getGet('page'),
                'search' => $this->input->getGet('search'),
                'company_id' => '',
                'work_unit_id' => '',
                'exclude_employee_id' => $this->input->getGet('exclude_employee_id'),
            )
        );

        echo json_encode($employee);
    }

    function send_mail_confirm($user_email, $user_password = '')
    {
        $data['user'] = $this->um->getDataById(md5($user_email));
        $data['user_password'] = $user_password;

        if ($data['user']) {
            $message = view('user/user_mail_confirm', $data);

            $config['protocol'] = 'smtp';
            $config['smtp_host'] = SMTP_HOST;
            $config['smtp_port'] = SMTP_PORT;
            $config['smtp_timeout'] = '7';
            $config['smtp_user'] = SMTP_USER;
            $config['smtp_pass'] = SMTP_PASS;
            $config['charset'] = 'utf-8';
            $config['newline'] = "\r\n";
            $config['mailtype'] = 'html';
            $config['validation'] = TRUE;

            $this->email->initialize($config);
            $this->email->from(SMTP_USER, EMAIL_ALIAS);
            $this->email->to($user_email);

            $this->email->subject('Anda diundang untuk menggunakan ' . APP_NAME);
            $this->email->message($message);

            if ($this->email->send()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function ceksendmail()
    {
        $config['protocol']    = 'smtp';
        $config['smtp_host']    = 'ssl://mail.arkamayaerp.id';
        $config['smtp_port']    = '465';
        $config['smtp_timeout'] = '7';
        $config['smtp_user']    = 'support@arkamayaerp.id';
        $config['smtp_pass']    = 'Rs[Uif_wKa_p';
        $config['charset']    = 'utf-8';
        $config['newline']    = "\r\n";
        $config['mailtype'] = 'text'; // or html
        $config['validation'] = TRUE; // bool whether to validate email or not      

        $this->email->initialize($config);


        $this->email->from('support@arkamayaerp.id', 'arkamayaerp.id');
        $this->email->to('irfan@arkamaya.co.id');


        $this->email->subject('Email Test');

        $this->email->message('Testing the email class.');

        $r = $this->email->send();
        if ($r) {
            echo 'sukses<hr/>';
        } else {
            echo 'gagal<hr/>';
        }

        echo $this->email->print_debugger();
    }

    // 20170407 irfan@arkamaya.co.id
    // untuk mengambil Pegawai yang akan di assign ke Pengguna
    function get_employee_to_assign()
    {
        $data = array(
            'items' => (array) $this->um->get_employee_to_assign($this->db->escapeString($_GET['search']))
        );

        echo json_encode($data);
        // $suggestions = array();
        // foreach ($results as $r)
        // {
        // 	$suggestions[] = array(
        // 		'data' => htmlspecialchars($r->employee_id),
        // 		'value' => htmlspecialchars($r->employee_name)
        // 	);
        // }
        // 
        // echo json_encode(array(
        // 	'query' => 'Unit'
        // 	, 'suggestions' => $suggestions
        // ));
    }

    function get_employee_by_company_id()
    {
        $data = array(
            'items' => (array) $this->um->get_employee_by_company($_REQUEST['company_id'])
        );

        echo json_encode($data);
    }

    function get_user_groups_by_company_id()
    {
        $data = array(
            'items' => (array) $this->usergroup_model->get_user_groups_by_company_id('', '', 'user_group_id desc', $_REQUEST['company_id'])
        );

        echo json_encode($data);
    }

    function getUserGroups()
    {
        if (isset($_POST["company_id"])) {

            $keyword = $this->input->getPost("keyword");
            $company_id = $this->input->getPost("company_id");

            $work_units = $this->um->getUserGroups($company_id, $keyword);

            echo json_encode($work_units);
        }
    }
}
