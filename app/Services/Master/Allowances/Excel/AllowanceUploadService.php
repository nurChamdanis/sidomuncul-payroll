<?php

namespace App\Services\Master\Allowances\Excel;

use App\Libraries\UploadExcelService;

class AllowanceUploadService extends UploadExcelService
{
    protected $fileLocation = WRITEPATH  . '/uploads/allowances/template/tmp/';
    protected $startRowIndex = 4;
    protected $currentRowIndex = 1;
}