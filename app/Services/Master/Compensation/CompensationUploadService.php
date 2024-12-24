<?php

namespace App\Services\Master\Compensation;

use App\Libraries\UploadExcelService;

class CompensationUploadService extends UploadExcelService
{
    protected $fileLocation = WRITEPATH  . '/uploads/compensations/template/tmp/';

    protected $startRowIndex = 4;
    protected $currentRowIndex = 1;
}