<?php

namespace App\Services\Master\Loan\Excel;

use App\Libraries\DownloadExcelService;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;

class LoanDownloadService extends DownloadExcelService
{
    /** Default Location */
    protected $fileLocation = WRITEPATH  . '/uploads/loan/template/';

    /** Properties */
    protected $title = 'Template Upload Master Loan';
    protected $creator = 'Industri Jamu Dan Farmasi Sido Muncul Tbk';
    protected $subject = 'Upload Data Master';
    protected $description = 'Template download for upload data Master Loan';
    protected $keywords = 'Uploads';
    protected $category = 'Upload Master';

    /** Properties Master Data */
    protected $companies = array();
    protected $loan_types = array();
    protected $loan_durations = array();

    public function __construct(array $companies, array $loan_type, array $loan_duration)
    {
        parent::__construct();
        
        $this->companies = $companies;
        $this->loan_types = $loan_type;
        $this->loan_durations = $loan_duration;
    }

    public function setHeaderUpload($sheet){
        $sheet->mergeCells('A1:I1');
        $sheet->setCellValue('A1', 'Template Master Loans');

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
        
        $sheet->mergeCells('E2:H2');
        $sheet->setCellValue('E2', 'Detail Pinjaman');
        $sheet->setCellValue('E3', 'Jenis Pinjaman');
        $sheet->setCellValue('F3', 'Durasi Pinjaman');
        $sheet->setCellValue('G3', 'Tanggal Awal Potong');
        $sheet->setCellValue('H3', 'Jumlah Pinjaman');

        $sheet->mergeCells('I2:I3');
        $sheet->setCellValue('I2', 'Keterangan');
        
        $this->setAlignment($sheet, 'A2:I3', 'horizontal', 'center');
        $this->setAlignment($sheet, 'A2:I3', 'vertical', 'center');
        $this->setBold($sheet, 'A2:I3', true);
        $this->setBorder($sheet, 'A2:I3');
        $this->setBackground($sheet, 'A2:I3', 'FFD966');

        foreach(range('A',$sheet->getHighestDataColumn()) as $columnID){
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    }

    /**Start Set Header Master */
    public function setHeaderMaster($sheet){
        $sheet->setTitle('Master');

        $this->setAlignment($sheet, 'A2:N2', 'horizontal', 'center');
        $this->setAlignment($sheet, 'A2:N2', 'vertical', 'center');
        $this->setBold($sheet, 'A2:N2', true);
        
        /**Master Perusahaan */
        $sheet->mergeCells('A1:B1');
        $sheet->setCellValue('A1', 'Master Perusahaan');
        $sheet->setCellValue('A2', 'ID');
        $sheet->setCellValue('B2', 'Perusahaan');

        $this->setBorder($sheet, 'A1:B2');
        $this->setBackground($sheet, 'A1:B2', 'FFF2CC');
        $this->setBold($sheet, 'A1:B2', true);

        $this->setAlignment($sheet, "A1:B1", 'horizontal', 'center');

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
        
        /**Master Jenis Pinjaman */
        $sheet->mergeCells('D1:E1');
        $sheet->setCellValue('D1', 'Master Jenis Pinjaman');
        $sheet->setCellValue('D2', 'ID');
        $sheet->setCellValue('E2', 'Jenis Pinjaman');

        $this->setAlignment($sheet, 'D1:E2', 'horizontal', 'center');
        $this->setBorder($sheet, 'D1:E2');
        $this->setBackground($sheet, 'D1:E2', 'FFF2CC');
        $this->setBold($sheet, 'D1:E2', true);
        
        if(!empty($this->loan_types)){
            $i = 3;
            foreach ($this->loan_types as $loan_type) {
                $sheet->setCellValue("D{$i}", $loan_type->system_code);
                $sheet->setCellValue("E{$i}", $loan_type->system_value_txt);
                $i++;
            }
            
            $totalRow =  $i - 1;
            
            $this->setBorder($sheet, "D3:E{$totalRow}");
            $this->setAlignment($sheet, "D3:E{$totalRow}", 'horizontal', 'center');
        }

        /**Master Jenis Pinjaman */

        /**Master Durasi Pinjaman */
        $sheet->mergeCells('G1:H1');
        $sheet->setCellValue('G1', 'Master Durasi Pinjaman');
        $sheet->setCellValue('G2', 'ID');
        $sheet->setCellValue('H2', 'Durasi Pinjaman');

        $this->setAlignment($sheet, 'G1:H2', 'horizontal', 'center');
        $this->setBorder($sheet, 'G1:H2');
        $this->setBackground($sheet, 'G1:H2', 'FFF2CC');
        $this->setBold($sheet, 'G1:H2', true);
        
        if(!empty($this->loan_durations)){
            $i = 3;
            foreach ($this->loan_durations as $loan_duration) {
                $sheet->setCellValue("G{$i}", $loan_duration->system_code);
                $sheet->setCellValue("H{$i}", $loan_duration->system_value_txt);
                $i++;
            }
            
            $totalRow =  $i - 1;
            
            $this->setBorder($sheet, "G3:H{$totalRow}");
            $this->setAlignment($sheet, "G3:H{$totalRow}", 'horizontal', 'center');
        }
        /**Master Jenis Pinjaman */

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