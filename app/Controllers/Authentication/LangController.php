<?php

namespace App\Controllers\Authentication;

use App\Controllers\BaseController;

class LangController extends BaseController
{
    protected $input;
    
    function __construct()
    {
        $this->input = service('request');
    }

    function set()
    {
        $lang_code = "EN";
        if(isset($_GET['lang_code'])){
            $lang_code = $_GET['lang_code'];
        }
        set_cookie('lang_code', $lang_code, '999600');
        echo json_encode($lang_code);
    }

    function get()
    {
        echo json_encode(get_cookie('lang_code',true));
    }
}