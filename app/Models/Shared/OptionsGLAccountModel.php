<?php

namespace App\Models\Shared;

use App\Models\Shared\OptionsModel;

class OptionsGLAccountModel extends OptionsModel
{
    protected $placeholder = 'Pilih GL Account';
    protected $fieldsSelect = 'gl_id,gl_code,gl_name';
    protected $table = 'tb_m_payroll_gl';
    protected $fieldsUsed = array('gl_id','gl_code','gl_name');
    protected $fieldsSearch = array('gl_name');
    protected $showPlaceholder = true;
    protected $dependOn = array('company_id');
}