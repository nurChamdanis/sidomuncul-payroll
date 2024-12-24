<?php

namespace App\Services\Master\Loan\Excel;

use App\Libraries\UploadExcelService;

class LoanUploadService extends UploadExcelService
{
    protected $fileLocation = WRITEPATH  . '/uploads/loan/template/tmp/';
    protected $startRowIndex = 4;
    protected $currentRowIndex = 1;
}