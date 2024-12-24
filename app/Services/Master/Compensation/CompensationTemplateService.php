<?php

namespace App\Services\Master\Compensation;

use App\Libraries\DownloadExcelService;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CompensationTemplateService extends DownloadExcelService
{
    /** Default Location */
    protected $fileLocation = WRITEPATH  . '/uploads/compensation/template/';

    /** Properties */
    protected $title = 'Template Upload Master Compensation';
    protected $creator = 'Industri Jamu Dan Farmasi Sido Muncul Tbk';
    protected $subject = 'Upload Data Master';
    protected $description = 'Template download for upload data Master Compensation';
    protected $keywords = 'Uploads';
    protected $category = 'Upload Master';

    /** Properties Master Data */
    protected $companies = array();
    protected $compensationType = array();

    public function __construct(array $companies, array $compensationType)
    {
        parent::__construct();
        
        $this->companies = $companies;
        $this->compensationType = $compensationType;
    }

    /**Start Set Header Upload */
    public function setHeaderUpload($sheet){
        $sheet->mergeCells('A1:M1');
        $sheet->setCellValue('A1', 'Template Master Compensation');

        $this->setAlignment($sheet, 'A1', 'horizontal', 'center');
        $this->setAlignment($sheet, 'A1', 'vertical', 'center');
        $this->setFontSize($sheet, 'A1', 14);
        $this->setBold($sheet, 'A1', true);

        $sheet->mergeCells('A2:A3');
        $sheet->setCellValue('A2', 'No');
        
        $sheet->mergeCells('B2:B3');
        $sheet->setCellValue('B2', 'Company Name');
        
        $sheet->mergeCells('C2:C3');
        $sheet->setCellValue('C2', 'Nomor Karyawan');
        
        $sheet->mergeCells('D2:D3');
        $sheet->setCellValue('D2', 'Nama Karyawan');
        
        $sheet->mergeCells('E2:G2');
        $sheet->setCellValue('E2', 'Detail Pinjaman');
        $sheet->setCellValue('E3', 'Jenis Kompensasi');
        $sheet->setCellValue('F3', 'Periode');
        $sheet->setCellValue('G3', 'Jumlah Pinjaman');

        $sheet->mergeCells('H2:H3');
        $sheet->setCellValue('H2', 'Keterangan');
        
        $this->setAlignment($sheet, 'A2:H3', 'horizontal', 'center');
        $this->setAlignment($sheet, 'A2:H3', 'vertical', 'center');
        $this->setBold($sheet, 'A2:H3', true);
        $this->setBorder($sheet, 'A2:H3');
        $this->setBackground($sheet, 'A2:H3', 'FFD966');

        foreach(range('A',$sheet->getHighestDataColumn()) as $columnID){
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    }
    /**Endc Set Header Upload */

    /**Start Set Header Master */
    public function setHeaderMaster($sheet){
        $sheet->setTitle('Master');

        $this->setAlignment($sheet, 'A1:E2', 'horizontal', 'center');
        $this->setAlignment($sheet, 'A1:E2', 'vertical', 'center');
        $this->setBold($sheet, 'A1:E2', true);
        
        /**Master Perusahaan */
        $sheet->mergeCells('A1:B1');
        $sheet->setCellValue('A1', 'Master Perusahaan');
        $sheet->setCellValue('A2', 'ID');
        $sheet->setCellValue('B2', 'Perusahaan');

        $this->setBorder($sheet, 'A1:B2');
        $this->setBackground($sheet, 'A1:B2', 'FFF2CC');
        $this->setBold($sheet, 'A1:B2', true);

        if(!empty($this->companies)){
            $i = 3;
            foreach ($this->companies as $company) {
                $sheet->setCellValue("A{$i}", $company->company_code);
                $sheet->setCellValue("B{$i}", $company->company_name);
                $i++;
            }

            $totalRow =  $i - 1;
            
            $this->setBorder($sheet, "A3:B{$totalRow}");
            $this->setAlignment($sheet, "A3:A{$totalRow}", 'horizontal', 'center');
        }
        /**Master Perusahaan */
        
        /**Master Kompensasi */
        $sheet->mergeCells('D1:E1');
        $sheet->setCellValue('D1', 'Master Jenis Kompensasi');
        $sheet->setCellValue('D2', 'ID');
        $sheet->setCellValue('E2', 'Jenis Pinjaman');

        $this->setAlignment($sheet, 'D1:E2', 'horizontal', 'center');
        $this->setBorder($sheet, 'D1:E2');
        $this->setBackground($sheet, 'D1:E2', 'FFF2CC');
        $this->setBold($sheet, 'D1:E2', true);
        
        if(!empty($this->compensationType)){
            $i = 3;
            foreach ($this->compensationType as $type) {
                $sheet->setCellValue("D{$i}", $type->system_code);
                $sheet->setCellValue("E{$i}", $type->system_value_txt);
                $i++;
            }
            
            $totalRow =  $i - 1;
            
            $this->setBorder($sheet, "D3:E{$totalRow}");
            $this->setAlignment($sheet, "D3:D{$totalRow}", 'horizontal', 'center');
        }
        /**Master Area */

        foreach(range('A',$sheet->getHighestDataColumn()) as $columnID){
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    }
    /**End Set Header Master */
    
    /**Start Generate */
    public function generate()
    {
        $spreadsheet = $this->spreadsheet;
        $spreadsheet->getActiveSheet()->setTitle('Upload');

        $this->setHeaderUpload($spreadsheet->getActiveSheet());
        $this->setHeaderMaster($spreadsheet->createSheet());
        
        return $this->writeToFile();
    }
    /**End Generate */
}