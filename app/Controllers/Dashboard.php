<?php

namespace App\Controllers;

use App\Core\MyController;
use App\Services\Shared\DataAccessService;

class Dashboard extends MyController
{
	protected $serviceDataAccess;

	public function __construct()
	{
		parent::__construct();
		$this->serviceDataAccess = new DataAccessService();
	}

    public function index(): string
    {
        $data = array_merge($this->data, get_title($this->menu, 'dashboard'));
		
		$data['task']				= 0;
		$data['task_onprogress']	= 0;
		$data['stitle'] = 'Dasbor Personalia';
		$data['jsapp'] = array();
		$data['start_dt_span'] = '';
		$data['end_dt_span'] = '';
		$data['start_dt'] = '';
		$data['end_dt'] = '';
		
        return view('dashboard/index', $data);
    }
}
