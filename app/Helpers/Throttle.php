<?php

namespace App\Helpers;

use App\Models\Authentication\ThrottleModel;
use App\Models\ThrottleModel\Authentication;
use stdClass;

class Throttle
{
    protected $throttle_model;
    protected $db;

    public function __construct()
    {
        $this->throttle_model = new ThrottleModel();
		$this->db = \Config\Database::connect();

        if (is_null(get_cookie('lang_code'))) {
            set_cookie('lang_code', 'EN', '999600');
        }

        $language = \Config\Services::language();
        $language->setLocale(get_cookie('lang_code',true));
    }

    /**
     * throttles check
     * @param int $type type of throttle to perform.
     *
     */
    public function throttle_check($type, $limit, $timeout, $ip, $user_email)
    {
        //clean up login attempts older than specified time
        $udin = $this->throttle_cleanup($timeout, $type, $user_email);

        $attempts = $this->throttle_model->where(['ip' => $ip, 'user_email' => $user_email])->countAllResults();

        if ($attempts > $limit) {

            ##UPDATE BY ARK.RAMADHANI, MEMBUAT COUNT DOWN, KETIKA REQUEST ATTEMPT, 2022-09-28      
            $builder = $this->db->table('tb_r_throttles');      
            $builder->select('created_at');
            $builder->where(['ip' => $ip, 'type' => $type, 'user_email' => $user_email]); 
            $builder->orderBy('created_at','desc');
            $results = $builder->limit(1)->get()->getResult();
    
            $date_now = date('Y-m-d H:i:s');
            $diff = round(abs(strtotime($results[0]->created_at) - strtotime($date_now)) / 60);

            // Kembalikan pesan
            // $msg = 'Percobaan terlalu banyak. Coba setelah ' . $diff . ' menit lagi.';
            $msg = lang('Login.attempt', array('$diff' => $diff));
            return [1,$msg];
            // Jika ingin ke laman 503 bawaan CI
            // show_error('Too many attempts. Try back after ' . $timeout . ' minutes.', 503, 'Attempt failed');
        } else {
            return [0, null];
        }
    }

    /**
     * throttles multiple connections attempts to prevent abuse
     * @param int $type type of throttle to perform.
     *
     */
    public function throttle_login($type = 0, $limit = 10, $timeout = 30, $ip = '', $user_email = '')
    {        
        //clean up login attempts older than specified time
        $this->throttle_cleanup($timeout, $type,$user_email);

        $data = new stdClass();
        // $data->ip = $this->CI->input->ip_address();
        $data->ip = $ip;
        $data->type = $type;
        $data->user_email = $user_email;
        $data->created_at = date('Y-m-d H:i:s', strtotime('+'.$timeout.' minutes'));

        $this->throttle_model->insert($data);

        $attempts = $this->throttle_model->where(['ip' => $ip, 'type' => $type, 'user_email' => $user_email])->countAllResults();
        // $get_timeout = $this->CI->throttle_model->where(['ip' => $ip, 'type' => $type, 'user_email' => $user_email])->limit(1)->get();
    
        if ($attempts > $limit) {

            ##UPDATE BY ARK.RAMADHANI, MEMBUAT COUNT DOWN, KETIKA REQUEST ATTEMPT, 2022-09-28
            $builder = $this->db->table('tb_r_throttles');
            $builder->select('created_at');
            $builder->where(['ip' => $ip, 'type' => $type, 'user_email' => $user_email]); 
            $builder->orderBy('created_at','desc');
            $results = $builder->limit(1)->get()->getResult();
    
            $date_now = date('Y-m-d H:i:s');
            $diff = round(abs(strtotime($results[0]->created_at) - strtotime($date_now)) / 60);

            // Kembalikan pesan
            // $msg = 'Percobaan terlalu banyak. Coba setelah ' . $diff . ' menit lagi.';
            $msg = lang('Login.attempt', array('$diff' => $diff));
            return [1,$msg];
            // Jika ingin ke laman 503 bawaan CI
            // show_error('Too many attempts. Try back after ' . $timeout . ' minutes.', 503, 'Attempt failed');
        }
        $msg = lang('Login.failed');
        return [0,$msg]; // return current number of attempted logins
    }

    /**
     * Cleans up old throttling attempts based on throttle timeout
     *
     * @param $timeout
     * @return result of query
     */
    public function throttle_cleanup($timeout, $type, $user_email)
    {
        $formatted_current_time = date("Y-m-d H:i:s", strtotime('-' . (int)$timeout . ' minutes'));
        $modifier =  " BETWEEN '1970-00-00 00:00:00' AND " . "'". $formatted_current_time . "'";
        $builder = $this->db->table('tb_r_throttles');
        $builder->where('created_at'.$modifier);
        
        return $builder->delete();

        // return $this->CI->throttle_model->where(['created_at' => $modifier, 'type' => $type, 'user_email' => $user_email])->delete();
    }

    /**
     * delete all old data throttling attempts based on throttle timeout
     *
     * @param $timeout
     * @return result of query
     */
    public function throttle_delete_all($ip, $user_email)
    {
        $builder = $this->db->table('tb_r_throttles');
        $builder->select('user_email');
        $builder->where(['ip' => $ip, 'user_email' => $user_email]); 
        $builder->delete();
    }
}