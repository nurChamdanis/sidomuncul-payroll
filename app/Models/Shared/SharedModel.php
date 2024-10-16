<?php

namespace App\Models\Shared;

use Exception;
use stdClass;

class SharedModel
{
	protected $db;
	protected $session;
	protected $input;
	protected $email;

	function __construct()
	{
		$this->db = \Config\Database::connect();
		$this->session = session();
		$this->input = service('request');
		$this->email = service('email');
	}

	function get_company_prefferences($system_type, $system_code, $order_by = '', $company_id = '')
	{
		$company_id = ($company_id == '') ? $this->session->get(S_COMPANY_ID) : $company_id;

		$sql = "
			select 
				system_value_txt, system_value_num, system_code
			from
				tb_m_company_prefferences
			where
				company_id = '" . $company_id . "'
		";


		$sql .= ($system_type != '') ? " and system_type = '" . $system_type . "'" : '';
		$sql .= ($system_code != '') ? " and system_code = '" . $system_code . "'" : '';

		$sql .= ($order_by != '') ? " order by " . $order_by : '';

		$sysval = $this->db->query($sql)->getResult();
		if (count($sysval) > 0) {
			return $sysval[0];
		} else {
			$row = new stdClass();
			$row->system_code = '';
			$row->system_value_txt = '';
			$row->system_value_num = '';
			return $row;
		}
	}

	function get_company_preferences_by_type($system_type, $company_id = '')
	{
		$company_id = ($company_id == '') ? $this->session->get(S_COMPANY_ID) : $company_id;

		//insert into company prefferences
		$sqlPreff = "insert into tb_m_company_prefferences(company_id, system_type, system_code, system_code_desc, system_value_txt, system_value_num, system_unit, created_by, created_dt)
		select DISTINCT " . $company_id . ", REPLACE(tb_m_system.system_type,'defcfg_',''), tb_m_system.system_code, tb_m_system.system_code_desc, tb_m_system.system_value_txt, tb_m_system.system_value_num, tb_m_system.system_unit, 'register', now()
		from tb_m_system 
		LEFT JOIN tb_m_company_prefferences ON 
			tb_m_company_prefferences.company_id = " . $company_id . " and REPLACE(tb_m_system.system_type,'defcfg_','') = tb_m_company_prefferences.system_type and tb_m_system.system_code = tb_m_company_prefferences.system_code
		where tb_m_system.system_type = 'defcfg_" . $system_type . "' 
		and tb_m_company_prefferences.preff_id is null
		";
		$this->db->query($sqlPreff);

		$sql = "
			select 
				*
			from
				tb_m_company_prefferences
			where
				company_id = '" . $company_id . "'
		";
		if ($system_type == 'bpjs_tk') {
			$sql .= "and system_type in ('" . $system_type . "', 'bpjs_tariff')";
			// $sql .= "and case
			// 	when system_type = 'bpjs_tariff' then system_value_txt
			// 	else '1' end
			// 	= '1' ";
		} else {
			$sql .= "and system_type = '" . $system_type . "'";
		}
		return $this->db->query($sql)->getResult();
	}

	function get_detail_pph21($p_employee_id, $p_company_id, $p_role_id, $p_role_salary_id, $p_position_id, $return_type, $p_bpjstk)
	{
		$data = array();
		// config PPH 21
		$config_payroll_pph21 = $this->db->query(
			"select system_code, coalesce(system_value_num,0) as system_value_num, coalesce(system_value_txt,'') as system_value_txt from tb_m_company_prefferences where system_type = 'config_payroll_pph21' and company_id = '" . $this->session->get(S_COMPANY_ID) . "'"
		)->getResult();
		foreach ($config_payroll_pph21 as $dt) {
			$data[$dt->system_code] = $dt->system_value_num;
		}
		// Set Tarif PKP
		$config_payroll_pkp = $this->db->query(
			"select system_code, coalesce(system_value_num,0) as system_value_num, coalesce(system_value_txt,'') as system_value_txt from tb_m_company_prefferences where system_type = 'config_payroll_pkp' and company_id = '" . $p_company_id . "'"
		)->getResult();
		foreach ($config_payroll_pkp as $dt) {
			$data[$dt->system_code . '_txt'] = $dt->system_value_txt;
			$data[$dt->system_code . '_num'] = $dt->system_value_num;
		}

		return $data;
	}
	function get_company_field($field)
	{
		$sql = "
			select " . $field . " from tb_m_company where company_id = '" . $this->session->get(S_COMPANY_ID) . "'
		";
		return $this->db->query($sql)->getResultArray();
	}

	function delete_company_preference($preff_id)
	{
		$builder = $this->db->table('tb_m_company_prefferences');
		$builder->where('preff_id', $preff_id);
		$builder->delete();
	}

	function check_duplicate_company_preference($system_type = '', $system_code = '', $company_id = '')
	{
		$company_id = ($company_id == '') ? $this->session->get(S_COMPANY_ID) : $company_id;
		$sql = "
			select 
				count(*) as cnt
			from
				tb_m_company_prefferences
			where
				1=1
		";
		$where = '';
		if ($system_type != '') {
			$where .= "and system_type = '" . $system_type . "'";
		}
		if ($system_code != '') {
			$where .= "and system_code = '" . $system_code . "'";
		}
		if ($company_id != '') {
			$where .= "and company_id = '" . $company_id . "'";
		}
		$sql .= $where;

		return $this->db->query($sql)->getRow()->cnt;
	}

	function save_company_preference()
	{
		$pref = array(
			'company_id' => $this->session->get(S_COMPANY_ID),
			'system_type' => $this->input->getPost('system_type', true),
			'system_code' => $this->input->getPost('system_value_txt', true),
			'system_value_txt' => $this->input->getPost('system_value_txt', true),
			'system_value_num' => $this->input->getPost('system_value_num', true)
		);

		if (
			$this->input->getPost('preff_id', true) == '' ||
			$this->input->getPost('preff_id', true) == null
		) {
			$pref['created_by'] = $this->session->get(S_EMPLOYEE_NAME);
			$pref['created_dt'] = date('Y-m-d H:i:s');

			$this->db->table('tb_m_company_prefferences')->insert($pref);
		} else {
			$pref_id = $this->input->getPost('preff_id', true);

			$pref['changed_by'] = $this->session->get(S_EMPLOYEE_NAME);
			$pref['changed_dt'] = date('Y-m-d H:i:s');

			// update header
			$this->db->table('tb_m_company_prefferences')->where('preff_id', $pref_id)->update('tb_m_company_prefferences', $pref);
		}
	}

	function get_company()
	{
		$sql = 'select * from tb_m_company where company_id = ' . $this->session->get(S_COMPANY_ID);
		return $this->db->query($sql)->getResult();
	}

	function get_list_company()
	{
		$sql = 'select company_id,company_name,company_code from tb_m_company';

		return $this->db->query($sql)->getResult();
	}

	function get_list_currency()
	{
		$sql = "select system_code, system_value_txt from tb_m_system where system_type = 'payment_currency'";

		return $this->db->query($sql)->getResult();
	}
	//hadi 10/03/2017
	function _dmy_to_ymd($dmy, $splitter)
	{
		$datex = explode($splitter, $dmy);
		return (count($datex) == 3) ? $datex[2] . '-' . $datex[1] . '-' . $datex[0] : null;
	}
	function _ymd_to_dmy($ymd, $splitter)
	{
		$datex = explode($splitter, $ymd);
		return (count($datex) == 3) ? $datex[2] . '/' . $datex[1] . '/' . $datex[0] : null;
	}

	function send_mail($view, $data, $subject, $user_email, $cc = '', $bcc = '', $send_flg = false)
	{
		$message = view($view, $data);

		if ($send_flg) {

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

			// cc to admin of company_id
			$this->email->cc($this->email_admin());

			if ($cc != '') {
				$email_admin = $this->email_admin();
				$this->email->cc($email_admin . ',' . $cc);
			}

			if ($bcc != '') {
				$this->email->bcc($bcc);
			}

			$this->email->subject($subject);
			$this->email->message($message);

			if ($this->email->send()) {
				return true;
			} else {
				// echo json_encode($this->email->print_debugger());
				return false;
			}
		} else {

			$arr_insert = array(
				"email_recipient" 	=> $user_email,
				"email_cc" 			=> $cc,
				"email_bcc" 		=> $bcc,
				"email_title" 		=> $subject,
				"email_contents" 	=> $message,
				"send_flg" 			=> "0",
				"created_by" 		=> $this->session->get(S_USER_NAME),
				"created_dt" 		=> date("Y-m-d H:i:s")
			);

			$this->db->table('tb_r_email_notification_queue')->insert($arr_insert);

			return $this->db->affectedRows();
		}
	}

	function email_admin()
	{
		return $this->db->query("select user_email from tb_m_company where company_id = '" . $this->session->get(S_COMPANY_ID) . "'")->getRow()->user_email;
	}

	function create_clog($function, $section, $employee_id, $text, $parent_id = '')
	{
		$this->db->query(
			"insert into tb_r_comment_log 
			(
				clog_function, clog_section, clog_employee_id, clog_parent_id, clog_dt, clog_text
			)
			values
			(
				'" . $function . "'
				, '" . $section . "'
				, '" . $employee_id . "'
				, '" . $parent_id . "'
				, NOW()
				, '" . $text . "'
			)
			"
		);
	}

	function get_clog($function, $section)
	{
		return $this->db->query("
			select 
				c.*
				, e.employee_name
				, e.photo
			from 
				tb_r_comment_log c
				inner join tb_m_employee e on e.employee_id = c.clog_employee_id
			where 
				clog_function = '" . $function . "' 
				and clog_section = '" . $section . "'
			order by
				clog_dt desc
		")->getResult();
	}

	function get_user_token($email_employee)
	{
		$sql = "
		SELECT
			token_firebase
		FROM
			tb_m_user_device
		WHERE
			user_email = '" . $email_employee . "'
		AND
			token_firebase != ''
		AND
			token_firebase != 'undefined'
		AND
			receive_notif = 1
		";

		$data = $this->db->query($sql)->getResult();

		$arr = array();

		foreach ($data as $aa) {
			array_push($arr, $aa->token_firebase);
		}

		return $arr;
	}

	function get_system($system_type, $system_code, $order_by = '')
	{
		$sql = "
			select 
				system_value_txt, system_value_num, system_code
			from
				tb_m_system
			where
				1 = 1
		";


		$sql .= ($system_type != '') ? " and system_type = '" . $system_type . "'" : '';
		$sql .= ($system_code != '') ? " and system_code = '" . $system_code . "'" : '';

		$sql .= ($order_by != '') ? " order by " . $order_by : '';
		$sysval = $this->db->query($sql)->getResult();
		if (count($sysval) > 0) {
			return $sysval[0];
		} else {
			$row = new stdClass();
			$row->system_code = '';
			$row->system_value_txt = '';
			$row->system_value_num = '';
			return $row;
		}
	}
	function generateInvoice()
	{

		$sql = "
			CALL sp_GenerateInvoice();
		";

		return $this->db->query($sql)->getResult();
	}
	function getSentEmailInvoice()
	{

		$sql = "
			select 
				c.user_email, i.* 
			from
				tb_r_invoice i
			JOIN tb_m_company c on i.company_id = c.company_id
			where
				(email_sent_flg is null or email_sent_flg = 0)
		";


		return $this->db->query($sql)->getResult();
	}
	function generateTimeoffExtend($company_id = 0)
	{
		try {
			$sql = "CALL sp_GenerateTimeOffExtend($company_id)";
			$query = $this->db->query($sql);

			if ($this->db->DBDriver === 'MySQLi') {
				if (mysqli_more_results($this->db->connID)) {
					mysqli_next_result($this->db->connID);
				}
			}

			$query->freeResult();
			return true;
		} catch (Exception $e) {
			// echo $e->getMessage();
			return false;
		}
	}

	function updateTimeoffExtend($company_id = 0, $employee_id = 0)
	{
		try {
			$sql = "CALL sp_UpdateExpiredTimeOff($company_id, $employee_id)";
			$query = $this->db->query($sql);

			if ($this->db->DBDriver === 'MySQLi') {
				// Move to the next result set
				if (mysqli_more_results($this->db->connID)) {
					mysqli_next_result($this->db->connID);
				}
			}

			$query->freeResult();
			return true;
		} catch (Exception $e) {
			// echo $e->getMessage();
			return false;
		}
		// function sync_leave($company_id){
		//     try {
		//         $this->db->query('CALL sp_GenerateTimeOffExtend('.$this->session->get(S_COMPANY_ID).')');
		//         $this->db->query('CALL sp_UpdateExpiredTimeOff('.$this->session->get(S_COMPANY_ID).',0)');
		//         return true;
		//     } catch (Exception $e) {
		//         echo $e->getMessage();
		//         return false;
		//     }
		// }
	}


	function generateAttendanceCustom($type = '', $employee_id = 0, $date = '')
	{
		try {
			// Determine the type of operation ('new' for generating attendance, 'update' for syncing attendance)
			// $type = 'new'; // Uncomment this line for generating attendance
			// $type = 'update'; // Uncomment this line for syncing attendance

			$sql = "CALL sp_GenerateAttendance('$type', $employee_id, '$date')";
			$query = $this->db->query($sql);

			// If you're using MySQLi as the underlying database driver
			if ($this->db->DBDriver === 'MySQLi') {
				// Move to the next result set (if any)
				while ($this->db->connID->more_results() && $this->db->connID->next_result()) {
					// Additional result sets found, continue looping
				}
			}

			// Free the result set
			$query->freeResult();

			return [
				"status" => true,
				"msg" => '',
				"sql" => $sql
			];
		} catch (Exception $e) {
			return array(
				"status" => false,
				"msg" => $e->getMessage()
			);
		}
	}


	function generateAttendance($type = '', $employee_id = 0, $date = '')
	{
		try {
			// Determine the type of operation ('new' for generating attendance, 'update' for syncing attendance)
			// $type = 'new'; // Uncomment this line for generating attendance
			// $type = 'update'; // Uncomment this line for syncing attendance

			$sql = "CALL sp_GenerateAttendance('$type', $employee_id, '$date')";
			$query = $this->db->query($sql);

			// If you're using MySQLi as the underlying database driver
			if ($this->db->DBDriver === 'MySQLi') {
				// Move to the next result set
				mysqli_next_result($this->db->connID);
			}

			// Free the result set
			$query->freeResult();

			return [
				"status" => true,
				"msg" => '',
				"sql" => $sql
			];
		} catch (Exception $e) {
			return array(
				"status" => false, "msg" => $e->getMessage()
			);
		}
	}

	function getListCompanyActive()
	{
		$sql = "
			SELECT 
				company_id,
				company_name				
			FROM 
				tb_m_company
			where 
				is_active =1 
		
		";
		return $this->db->query($sql)->getResult();
	}

	function getNotifications($page = 1)
	{
		$perPage = 10; // Jumlah item per halaman
		$offset = ($page - 1) * $perPage;

		$sql = "
		SELECT 
			notification_id
			, company_id
			, notification_date
			, CASE WHEN '" . get_cookie('lang_code', true) . "' = 'ID' THEN COALESCE(notification_title_id, notification_title) ELSE notification_title END AS notification_title 
			, CASE WHEN '" . get_cookie('lang_code', true) . "' = 'ID' THEN COALESCE(notification_content_id, notification_content) ELSE notification_content END AS notification_content 
			, link_location
			, ifnull(read_flg, '0') as read_flg
		FROM tb_r_notification trn
		WHERE notification_to = " . $this->session->get(S_EMPLOYEE_ID) . "
		ORDER BY read_flg asc, created_dt desc
		LIMIT " . $offset . "," . $perPage . ";";

		return $this->db->query($sql)->getResult();
	}

	function countNotifications()
	{
		$sql = "select count(*) as counter from tb_r_notification trn where notification_to = " . $this->session->get(S_EMPLOYEE_ID) . " and read_flg = 0";
		$query = $this->db->query($sql)->getRow()->counter;
		return $query;
	}

	function readAllNotifications()
	{
		// Assuming $this->session->get(S_EMPLOYEE_ID) gets the value of the session variable containing employee ID
		$notification_to = $this->session->get('S_EMPLOYEE_ID');

		// Define the data to be updated
		$updateData = [
			'read_flg' => 1
		];

		// Perform the update with conditions
		$this->db->table('tb_r_notification')
			->where('notification_to', $notification_to)
			->where('read_flg', 0)
			->update($updateData);
	}

	function readNotifications()
	{
		$notification_id = $this->input->getPost('notification_id');

		// Define the data to be updated
		$updateData = [
			'read_flg' => 1
		];

		// Perform the update with conditions
		$this->db->table('tb_r_notification')
			->where('notification_id', $notification_id)
			->update($updateData);
	}

	function sendNotifications($user_id, $notification_title, $notification_content, $link_location, $notification_title_en = '', $notification_content_en = '')
	{
		$sql = "
			insert into tb_r_notification
			(
				company_id
				, notification_date
				, notification_from
				, notification_to
				, notification_title
				, notification_content
				, notification_title_id
				, notification_content_id
				, link_location
				, created_by
				, created_dt
			)
			values
			(
				'" . $this->session->get(S_COMPANY_ID) . "'
				, '" . date('Y-m-d H:i:s') . "'
				, '" . $this->session->get(S_EMPLOYEE_ID) . "'
				, '" . $user_id . "'
				, '" . $notification_title_en . "'
				, '" . $notification_content_en . "'
				, '" . $notification_title . "'
				, '" . $notification_content . "'
				, '" . $link_location . "'
				, '" . $this->session->get(S_USER_NAME) . "'
				, '" . date("Y-m-d H:i:s") . "'
			)
		";
		$this->db->query($sql);
		return  true;
	}

	function insertFtpUpload($data)
	{
		// ftp_log_file
		// ftp_log_type
		$data['ftp_log_date'] = date('Y-m-d H:i:s');
		// $data['ftp_uploaded_dt'] = date('Y-m-d H:i:s');
		$data['ftp_uploaded_sts'] = '0';
		$data['created_by'] = $this->session->get(S_USER_NAME) ? $this->session->get(S_USER_NAME) : 'system';
		$data['created_dt'] = date('Y-m-d H:i:s');
		// die;
		$this->db->table('tb_h_ftp_upload')->insert($data);
	}

	function getEmailQueue()
	{

		// Delete Old Data
		$sql = "
			delete FROM `tb_r_email_notification_queue` where created_dt < DATE_ADD(CURDATE(), INTERVAL -3 DAY) or ifnull(email_recipient, '') = ''
		";
		$this->db->query($sql);

		// Get Queue Email
		$sql = "
			SELECT email_queue_id, email_recipient, email_cc, email_bcc, email_title, email_contents, send_flg
			FROM tb_r_email_notification_queue
			where send_flg = '0' order by created_dt asc
			limit 15
		";

		return $this->db->query($sql)->getResult();
	}

	function updateSendFlg($data)
	{
		$updateKey = 'email_queue_id'; // Key to match records for update

		foreach ($data as $row) {
			$builder = $this->db->table('tb_r_email_notification_queue');
			$builder->where($updateKey, $row[$updateKey]);
			unset($row[$updateKey]); // Remove the key used for update
			$builder->set($row);
			$builder->update();
		}
	}

	function sendJobEmail($user_email, $subject, $message, $cc = '', $bcc = '')
	{
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

		if ($cc != '') {
			$this->email->cc($cc);
		}

		if ($bcc != '') {
			$this->email->bcc($bcc);
		}

		$this->email->subject($subject);
		$this->email->message($message);

		if ($this->email->send()) {
			return true;
		} else {
			// echo json_encode($this->email->print_debugger());
			return false;
		}
	}
}
