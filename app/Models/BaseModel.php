<?php

namespace App\Models;

class BaseModel
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
}