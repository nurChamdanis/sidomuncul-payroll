<?php

namespace App\Services\Master\Deductions\Excel;

use App\Libraries\UploadExcelService;

class DeductionUploadService extends UploadExcelService
{
    protected $fileLocation = WRITEPATH  . '/uploads/deductions/template/tmp/';

    protected $startRowIndex = 4;
    protected $currentRowIndex = 1;
}