<?php

namespace App\Core;

/**
 * @author misbah@arkamaya.co.id
 * @since 2017-02-06 
 * --------------------------------
 * @modified luthfi.aziz@arkamaya.co.id on May 2024
 */

use App\Controllers\BaseController;
use App\Libraries\Permission;
use App\Models\Shared\SharedModel;

class MyController extends BaseController{
    /**
     *  Define Root Variables
     */
    
    // Utility variables
    protected $uri;
    protected $session;
    protected $input;
    protected $email;
    protected $db;

    // role & permission, shared model instance
    protected $permission; 
    protected $menu;
    protected $sm; 

    /**
     * Load Initial Data like notification count, notifications, menus, etc...
     */
    public $notifcount = 0;
    public $data = array(
        'notifcount' => 0,
        'notifications' => array(),
        'menus' => array(),
        'permissions' => array(),
        'button' => array(),
        'menu' => array(),
        'company' => array(),
    );

    public function __construct()
    {
        header("Content-Security-Policy: default-src 'self' 'unsafe-inline' 'unsafe-eval' data: blob:; script-src 'self' 'unsafe-inline' 'unsafe-eval' data: maps.googleapis.com https://client.crisp.chat https://settings.crisp.chat https://app.sandbox.midtrans.com/; font-src * data:; style-src 'self' 'unsafe-inline' 'unsafe-eval' https://fonts.googleapis.com https://client.crisp.chat; img-src * data:; media-src 'self' 'unsafe-inline' 'unsafe-eval' https://client.crisp.chat; frame-src *; connect-src 'self' 'unsafe-inline' 'unsafe-eval' wss://client.relay.crisp.chat https://maps.googleapis.com");
        
        /**
         * Loaded shared model and permission
         */
        $this->permission = new Permission();
        $this->sm = new SharedModel();
        
        /**
         * Loaded Permissions
         */
        if ($this->permission->get_user_permissions() === false)
        {
            show_401();
        }
        else{
            $this->data['company'] = $this->sm->get_company();
            $this->data['button'] = $this->permission->get_access_button();
            $this->data['menu'] = $this->permission->get_access_menu();
            $this->data['notifcount'] = $this->sm->countNotifications();
            $this->data['notifications'] = $this->sm->getNotifications();
            $this->data['permissions'] = $this->permission->get_user_permissions();	
            $this->data['session'] = session();	
            $this->data['uri'] = service('uri');	

            $this->menu = $this->data['menu'];
        }
        
        /**
         * Loaded standar helpers & services
         */
        $this->session = session();
        $this->input = service('request');
        $this->email = service('email');
		$this->db = \Config\Database::connect();
        $this->uri = service('uri');
        
        /**
         * Load Default Locale Language
         */
        if (is_null(get_cookie('lang_code'))) {
            set_cookie('lang_code', 'en', '999600');
        }
        $this->session->set('lang', get_cookie('lang_code',true));
    }
}
