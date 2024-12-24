<?php

namespace App\Controllers\Authentication;

use App\Controllers\MyBaseController;
use App\Core\MyBaseController as CoreMyBaseController;
use App\Helpers\Throttle;
use App\Helpers\GetLang;
use App\Models\Authentication\LoginModel as AuthenticationLoginModel;
use App\Models\Shared\SharedModel;
use App\Services\Shared\DataAccessService;
use LoginModel;

class LoginController extends CoreMyBaseController
{
    protected $login_model;

    public function __construct()
    {
        parent::__construct();
        
        // Load Login Model
        $this->login_model = new AuthenticationLoginModel();
    }

    public function index()
    {
        $shared = new SharedModel();
            
        if ($this->session->get(S_COMPANY_ID) != null) {
            return redirect($this->session->get(S_DEFAULT_LANDING), 'refresh');
        } else {
            $data['appstore'] = $shared->get_system('portal_app', 'appstore')->system_value_txt;
            $data['googleplay'] = $shared->get_system('portal_app', 'googleplay')->system_value_txt;
            return view('authentication/login', $data);
        }
    }

    function out($next = '')
    {
        $this->session->destroy();
        return redirect('login');
    }

    public function check()
    {
        //update by uye, fix sql injection on auth_user        
        // update by bagia, fix sql injection
        $password = md5($this->db->escapeString($this->input->getPost('user_password')));

        if (filter_var($this->db->escapeString($this->input->getPost('user_name')))) {
            $user_email = $this->db->escapeString($this->input->getPost('user_name'));
            $user_data = $this->login_model->auth_user($this->db->escapeString($this->input->getPost('user_name')));
            
            if (count($user_data) > 0) {
                $user_data = $user_data[0];

                // Check table tb_r_throttles exists
                if ($this->db->tableExists('tb_r_throttles')) {
                    // updated by arka.rangga, Add throttle Library, insert and record data based on user_email and ip_address
                    // Load Throttle Library

                    // getIpAddress function di common_helper
                    $ip = getIpAddress();

                    // limit diambil dari tb_m_system => system_value_txt
                    // timeout diambil dari tb_m_system => system_value_num
                    $builder = $this->db->table('tb_m_system');
                    $builder->select('system_value_txt,system_value_num');
                    $builder->where('system_type', 'login_attempt');
                    $timeout = $builder->get()->getResult()[0];

                    // Arka.Rangga, check tb_r_throttle, jika user sedang di-restrict karena login attempt melebihi limit
                    // throttle_check(type, limit per hout, timeout, ip_address, user_email)
                    $check_attempt = $this->throttle->throttle_check(5, $timeout->system_value_txt, $timeout->system_value_num, $ip, $user_email);
                    if ($check_attempt[0] == 1) {
                        $this->session->set('attempt', $check_attempt[1]);
                        return redirect('login/attempt');
                    } else {
                        if ($user_data->user_password == $password) {
                            $this->login_model->last_logged_in($user_data->user_name);

                            if(!empty($user_data->access_approval_id)){
                                $user_data->access_approval_id = ltrim($user_data->access_approval_id, ",");
                            }

                            // employee_name ada jika pengguna di LINK kan ke pegawai
                            $employee_name = ($user_data->employee_name != '') ? $user_data->employee_name : 'Pengguna';

                            $this->session->set(S_COMPANY_ID, $user_data->company_id);
                            $this->session->set(S_COMPANY_NAME, $user_data->company_name);
                            $this->session->set(S_USER_NAME, $user_data->user_name);
                            $this->session->set(S_USER_GROUP_ID, $user_data->user_group_id);
                            $this->session->set(S_PHOTO, $user_data->photo);
                            $this->session->set(S_EMPLOYEE_ID, $user_data->employee_id);
                            $this->session->set(S_EMPLOYEE_NAME, $employee_name);
                            $this->session->set(S_IS_ADMIN, $user_data->is_admin);
                            $this->session->set(S_DEFAULT_LANDING, $user_data->default_landing);
                            $this->session->set(S_IS_EXPIRED, $user_data->is_expired);
                            $this->session->set(S_TRIAL_EXPIRED, $user_data->trial_expired);
                            $this->session->set(S_POSITION_ID, $user_data->position_id);
                            $this->session->set(S_POSITION_NAME, $user_data->position_name);
                            $this->session->set(S_LANGUAGE_DEFAULT, $user_data->lang_id == "18" ? "ID" : "EN");
                            $this->session->set(S_NO_REG, $user_data->no_reg);

                            // data access
                            if($user_data->access_company_id != '' || $user_data->access_area_id != '' || $user_data->access_position_id != '' || $user_data->access_role_id != '' || $user_data->access_subordinate_id != ''){
                                $approver_delegation = $this->login_model->get_approver_delegation($user_data->user_name, $user_data->employee_id);
                                if($user_data->access_company_id != '' && $approver_delegation->access_company_id){
                                    $tmp = array_unique(array_merge(explode(",", $user_data->access_company_id), explode(",", $approver_delegation->access_company_id)));
                                    $user_data->access_company_id = implode(",", $tmp);
                                }
                                if($user_data->access_area_id != '' && $approver_delegation->access_area_id != ''){
                                    $tmp = array_unique(array_merge(explode(",", $user_data->access_area_id), explode(",", $approver_delegation->access_area_id)));
                                    $user_data->access_area_id = implode(",", $tmp);
                                }
                                if($user_data->access_position_id != '' && $approver_delegation->access_position_id){
                                    $tmp = array_unique(array_merge(explode(",", $user_data->access_position_id), explode(",", $approver_delegation->access_position_id)));
                                    $user_data->access_position_id = implode(",", $tmp);
                                }
                                if($user_data->access_role_id != '' && $approver_delegation->access_role_id != ''){
                                    $tmp = array_unique(array_merge(explode(",", $user_data->access_role_id), explode(",", $approver_delegation->access_role_id)));
                                    $user_data->access_role_id = implode(",", $tmp);
                                }
                                if($user_data->access_subordinate_id != '' && $approver_delegation->access_subordinate_id){
                                    $tmp = array_unique(array_merge(explode(",", $user_data->access_subordinate_id), explode(",", $approver_delegation->access_subordinate_id)));
                                    $user_data->access_subordinate_id = implode(",", $tmp);
                                }
                            }
                            $this->session->set(S_ACCESS_COMPANY_ID, $user_data->access_company_id);
                            $this->session->set(S_ACCESS_AREA_ID, $user_data->access_area_id);
                            $this->session->set(S_ACCESS_POSITION_ID, $user_data->access_position_id);
                            $this->session->set(S_ACCESS_ROLE_ID, $user_data->access_role_id);
                            $this->session->set(S_ACCESS_SUBORDINATE_ID, $user_data->access_subordinate_id);
                            $this->session->set(S_ACCESS_APPROVAL_ID, $user_data->access_approval_id);

                            $this->session->set(S_CAN_ACCESS_PAYROLL_APP, $user_data->access_payroll_app);
                            
                            $userGroupDelegate = $this->login_model->getDelegate($user_data->user_email);
                            if(!empty($userGroupDelegate)){
                                $usergroupIds = array_merge(array($user_data->user_group_id), array_values($userGroupDelegate));
                            } else {
                                $usergroupIds[] = $user_data->user_group_id;
                            }
                            
                            $this->login_model->getAccessData($user_data->no_reg, $user_data->employee_id, array_values($usergroupIds), $user_data->is_admin);
                                                        
                            // language
                            if (is_dir('application/language/' . $user_data->lang_code)) {
                                $this->session->set(S_LANG_CODE, $user_data->lang_code);
                            } else {
                                $this->session->set(S_LANG_CODE, 'ID');
                            }
                            // languange

                            // for pdf password needs
                            $string = $this->input->getPost('user_password');

                            $this->session->set('Session_id', $this->getPwdEncrypted($string));

                            $this->session->set(SIDOKEY, $this->login_model->getEncryptionKey());

                            $redirect_uri = $this->input->getPost('redirect_uri');
                            $this->throttle->throttle_delete_all($ip, $user_email);

                            // update @moharifrifai : set session jika sudah daftar affiliate
                            // $user_data_affiliate = $this->affiliate_model->auth_user($this->db->escape_str($this->input->getPost('user_name')));

                            // if (count($user_data_affiliate) > 0) {
                            //     $user_data_affiliate = $user_data_affiliate[0];
                            //     $this->affiliate_model->last_logged_in($user_data_affiliate->affiliate_email);

                            //     $this->session->set(S_AFFILIATE_ID, $user_data_affiliate->affiliate_id);
                            //     $this->session->set(S_AFFILIATE_NO, $user_data_affiliate->affiliate_no);
                            //     $this->session->set(S_AFFILIATE_NAME, $user_data_affiliate->affiliate_name);
                            //     $this->session->set(S_AFFILIATE_PHONE, $user_data_affiliate->affiliate_phone);
                            //     $this->session->set(S_AFFILIATE_EMAIL, $user_data_affiliate->affiliate_email);
                            //     $this->session->set(S_REFERRAL_CODE, $user_data_affiliate->referral_code);
                            // }

                            if ($redirect_uri != '') {
                                return redirect()->to($redirect_uri);
                            } else {
                                return redirect()->to($user_data->default_landing);
                            }
                        } else {
                            // echo 'password not same';
                            // echo $user_data->user_password;
                            // echo $password;

                            // throttle_login(type[optional], limit per hour, timeout dlnm menit,ip,user_email)
                            $attempts = $this->throttle->throttle_login(5, $timeout->system_value_txt, $timeout->system_value_num, $ip, $user_email);

                            // Jika true atau 1, maka over limit attempts
                            if ($attempts[0] == 1) {
                                $this->session->set('attempt', $attempts[1]);
                                return redirect('login/attempt');
                            } else {
                                return redirect('login/failed');
                            }
                            // redirect('login/failed');
                        }
                    }
                } // if tb_r_throttles exists
                else {
                    // Jika tabel tb_r_throttles gk ada
                    show_error('We apologize for your inconvenience. Web Apps are still under construction', 503, 'Under Construction.');
                }
            } else {
                // echo 'account not found';
                // echo $user_data->user_password;
                // echo $password;
                return redirect('login/failed');
            }
        } else {
            redirect('login/failed');
        }
    }

    function forgot_password()
    {
        if (isset($_POST['email'])) {
            helper('string');
            $email   = $this->input->getPost('email');
            $row     = $this->login_model->getEmployeeByUname($email);
            if ($row != false) {
                if ($row->email_confirmed == '1') {
                    $code = md5(random_string('alnum', 8));
                    $data['employee_name'] = $row->full_name;
                    $data['email'] = $email;
                    $data['link'] = base_url() . 'reset_password/?k=' . $code;

                    $emp['changed_by'] = $email;
                    $emp['changed_dt'] = date('Y-m-d H:i:s');
                    $emp['reset_password_code'] = $code;

                    $this->login_model->reset_password($emp, $email, false);
                    $sent_email = $this->sent_email_reset_password($data, 'link_forgot');

                    if ($sent_email) {
                        $this->session->set_flashdata('notif_status', 'success');
                        $this->session->set_flashdata('notif_forgot_pass', lang('Login.email_send_success'));
                        redirect('forgot_password');
                    } else {
                        $this->session->set_flashdata('notif_status', 'error');
                        $this->session->set_flashdata('notif_forgot_pass', lang('Login.email_not_sent'));
                        redirect('forgot_password');
                    }
                } else {
                    $this->session->set_flashdata('notif_status', 'error');
                    $this->session->set_flashdata('notif_forgot_pass', lang('Login.email_not_confirmed'));
                }
            } else {
                $this->session->set_flashdata('notif_status', 'error');
                $this->session->set_flashdata('notif_forgot_pass', lang('Login.email_not_registered'));
            }
        }

        return view('authentication/forgot_password');
    }

    function reset_password()
    {
        $k = $this->input->get('k');
        if (!$k) {
            redirect('forgot_password');
        }

        $um = $this->login_model->get_email_by_reset_password_code($k);
        if (!$um) {
            $this->session->set_flashdata('notif_status', 'error');
            $this->session->set_flashdata('notif_forgot_pass', lang('Login.reset_password_code_not_found'));
            redirect('forgot_password');
        }

        if (isset($_POST['email'])) {
            $email                           = $this->input->getPost('email');
            $newpassword                     = $this->input->getPost('newpassword');
            $key                             = $this->input->getPost('key');
            $data['user_password']           = md5($newpassword);
            $data['reset_password_code']     = null;

            $res = $this->login_model->reset_password($data, $email, true, $key);
            if ($res > 0) {
                $this->session->set_flashdata('notif_status', 'success');
                $this->session->set_flashdata('notif_reset_pass', lang('Login.reset_password_success', array('$link' => site_url('login'))));
            } else {
                $this->session->set_flashdata('notif_status', 'error');
                $this->session->set_flashdata('notif_reset_pass', lang('Login.reset_password_failed', array('$link' => site_url('forgot_password'))));
            }
        }
        $data['key']    = $k;
        $data['um']     = $um;

        return view('reset_password', $data);
    }

    function sent_email_reset_password($data)
    {
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = SMTP_HOST;
        $config['smtp_port'] = SMTP_PORT;
        $config['smtp_timeout'] = '30';
        $config['smtp_user'] = SMTP_USER;
        $config['smtp_pass'] = SMTP_PASS;
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['mailtype'] = 'html';

        $this->email->initialize($config);
        $this->email->from(SMTP_USER, EMAIL_ALIAS);
        $this->email->to($data['email']);


        $message = view('mail_reset_password', $data);
        $this->email->subject('Permintaan Ubah Kata Sandi ' . APP_NAME);
        $this->email->message($message);

        return $this->email->send();
    }

    function getPwdEncrypted($string)
    {
        $ciphering = "AES-256-CTR";

        $iv = openssl_random_pseudo_bytes(16, $wasItSecure);

        $encrypted = base64_encode($iv . openssl_encrypt(
            $string,
            $ciphering,
            ENCRYPTKEY,
            0,
            $iv
        ));

        return $encrypted;
    }

    function getPwdDecrypted($encrypted)
    {
        $ciphering = "AES-256-CTR";

        $data = base64_decode($encrypted);
        $iv = substr($data, 0, openssl_cipher_iv_length($ciphering));

        $decrypted = openssl_decrypt(
            substr($data, openssl_cipher_iv_length($ciphering)),
            $ciphering,
            ENCRYPTKEY,
            0,
            $iv
        );
        return $decrypted;
    }
}
