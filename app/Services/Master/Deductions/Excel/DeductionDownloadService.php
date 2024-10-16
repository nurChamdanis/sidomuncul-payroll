<?php

namespace App\Services\Master\Deductions\Excel;

use App\Libraries\DownloadExcelService;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DeductionDownloadService extends DownloadExcelService
{
    /** Default Location */
    protected $fileLocation = WRITEPATH  . '/uploads/deductions/template/';

    /** Properties */
    protected $title = 'Template Upload Master Deductions';
    protected $creator = 'Industri Jamu Dan Farmasi Sido Muncul Tbk';
    protected $subject = 'Upload Data Master';
    protected $description = 'Template download for upload data Master Deduction';
    protected $keywords = 'Uploads';
    protected $category = 'Upload Master';

    /** Properties Master Data */
    protected $companies = array();
    protected $area = array();
    protected $areaGroup = array();
    protected $rules = array();

    public function __construct(array $companies, array $area, array $areaGroup, array $rules)
    {
        parent::__construct();
        
        $this->companies = $companies;
        $this->area = $area;
        $this->areaGroup = $areaGroup;
        $this->rules = $rules;
    }

    /**Start Set Header Upload */
    public function setHeaderUpload($sheet){
        $sheet->mergeCells('A1:L1');
        $sheet->setCellValue('A1', 'Template Master Deductions');

        $this->setAlignment($sheet, 'A1', 'horizontal', 'center');
        $this->setAlignment($sheet, 'A1', 'vertical', 'center');
        $this->setFontSize($sheet, 'A1', 14);
        $this->setBold($sheet, 'A1', true);

        $sheet->mergeCells('A2:A3');
        $sheet->setCellValue('A2', 'No');
        
        $sheet->mergeCells('B2:B3');
        $sheet->setCellValue('B2', 'Company Name');
        
        $sheet->mergeCells('C2:E2');
        $sheet->setCellValue('C2', 'Deductions');
        $sheet->setCellValue('C3', 'Code');
        $sheet->setCellValue('D3', 'Name');
        $sheet->setCellValue('E3', 'Default Value');
        
        $sheet->mergeCells('F2:H2');
        $sheet->setCellValue('F2', 'Configuration');
        $sheet->setCellValue('F3', 'Calculation Type');
        $sheet->setCellValue('G3', 'Calculation Mode');
        $sheet->setCellValue('H3', 'GL Account');

        $sheet->mergeCells('I2:I3');
        $sheet->setCellValue('I2', 'Effective Date');
        
        $sheet->mergeCells('J2:J2');
        $sheet->setCellValue('J2', 'Access');
        $sheet->setCellValue('J3', 'Area');
        $sheet->setCellValue('K3', 'Grup');

        $sheet->mergeCells('L2:L3');
        $sheet->setCellValue('L2', 'Rules');
        
        $this->setAlignment($sheet, 'A2:L3', 'horizontal', 'center');
        $this->setAlignment($sheet, 'A2:L3', 'vertical', 'center');
        $this->setBold($sheet, 'A2:L3', true);
        $this->setBorder($sheet, 'A2:L3');
        $this->setBackground($sheet, 'A2:L3', 'FFD966');

        foreach(range('A',$sheet->getHighestDataColumn()) as $columnID){
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    }
    /**Endc Set Header Upload */

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
        
        /**Master Area */
        $sheet->mergeCells('D1:F1');
        $sheet->setCellValue('D1', 'Master Area');
        $sheet->setCellValue('D2', 'Perusahaan');
        $sheet->setCellValue('E2', 'ID');
        $sheet->setCellValue('F2', 'Area');

        $this->setAlignment($sheet, 'D1:F2', 'horizontal', 'center');
        $this->setBorder($sheet, 'D1:F2');
        $this->setBackground($sheet, 'D1:F2', 'FFF2CC');
        $this->setBold($sheet, 'D1:F2', true);
        
        if(!empty($this->area)){
            $i = 3;
            foreach ($this->area as $area) {
                $sheet->setCellValue("D{$i}", $area->company_name);
                $sheet->setCellValue("E{$i}", $area->code);
                $sheet->setCellValue("F{$i}", $area->name);
                $i++;
            }
            
            $totalRow =  $i - 1;
            
            $this->setBorder($sheet, "D3:F{$totalRow}");
            $this->setAlignment($sheet, "E3:E{$totalRow}", 'horizontal', 'center');
        }
        /**Master Area */
        
        /**Master Area */
        $sheet->mergeCells('H1:J1');
        $sheet->setCellValue('H1', 'Master Grup');
        $sheet->setCellValue('H2', 'Perusahaan');
        $sheet->setCellValue('I2', 'ID');
        $sheet->setCellValue('J2', 'Grup');

        $this->setAlignment($sheet, 'H1:J2', 'horizontal', 'center');
        $this->setBorder($sheet, 'H1:J2');
        $this->setBackground($sheet, 'H1:J2', 'FFF2CC');
        $this->setBold($sheet, 'H1:J2', true);
        
        if(!empty($this->areaGroup)){
            $i = 3;
            foreach ($this->areaGroup as $areaGroup) {
                $sheet->setCellValue("H{$i}", isset($this->companies[0]) ? $this->companies[0]->company_name : '');
                $sheet->setCellValue("I{$i}", $areaGroup->system_code);
                $sheet->setCellValue("J{$i}", $areaGroup->system_value_txt);
                $i++;
            }
            
            $totalRow =  $i - 1;
            
            $this->setBorder($sheet, "H3:J{$totalRow}");
            $this->setAlignment($sheet, "I3:I{$totalRow}", 'horizontal', 'center');
        }
        /**Master Grup */
        
        /**Master Rules */
        $sheet->mergeCells('L1:N1');
        $sheet->setCellValue('L1', 'Master Rules');
        $sheet->setCellValue('L2', 'Perusahaan');
        $sheet->setCellValue('M2', 'ID');
        $sheet->setCellValue('N2', 'Rules');

        $this->setAlignment($sheet, 'L1:N2', 'horizontal', 'center');
        $this->setBorder($sheet, 'L1:N2');
        $this->setBackground($sheet, 'L1:N2', 'FFF2CC');
        $this->setBold($sheet, 'L1:N2', true);
        
        if(!empty($this->rules)){
            $i = 3;
            foreach ($this->rules as $rule) {
                $sheet->setCellValue("L{$i}", $rule['company_name']);
                $sheet->setCellValue("M{$i}", $rule['rules_code']);
                $sheet->setCellValue("N{$i}", $rule['rules_name']);
                $i++;
            }
            
            $totalRow =  $i - 1;
            
            $this->setBorder($sheet, "L3:N{$totalRow}");
            $this->setAlignment($sheet, "M3:M{$totalRow}", 'horizontal', 'center');
        }
        /**Master Rules */

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