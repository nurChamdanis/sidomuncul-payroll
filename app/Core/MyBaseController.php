<?php

namespace App\Core;

/**
 * @author misbah@arkamaya.co.id
 * @since 2017-02-06 
 * --------------------------------
 * @modified luthfi.aziz@arkamaya.co.id on May 2024
 */

use App\Controllers\BaseController;
use App\Helpers\Throttle;

class MyBaseController extends BaseController{
    /**
     *  Define Root Variables
     */
    
    // Utility variables
    protected $session;
    protected $input;
    protected $email;
    protected $db;
    protected $throttle;

    public function __construct()
    { 
        /**
         * Loaded standar helpers & services
         */
        $this->session = session();
        $this->input = service('request');
        $this->email = service('email');
		$this->db = \Config\Database::connect();
        $this->throttle = new Throttle();
        
        /**
         * Load Default Locale Language
         */
        
        if (is_null(get_cookie('lang_code'))) {
            set_cookie('lang_code', 'en ', '999600');
        }

        $this->session->set('lang', get_cookie('lang_code',true));
    }
}