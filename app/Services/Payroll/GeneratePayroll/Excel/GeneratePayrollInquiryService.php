<?php

namespace App\Services\Payroll\GeneratePayroll\Excel;

use App\Libraries\SimpleExcelService;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\BorderStyle;

class GeneratePayrollInquiryService extends SimpleExcelService
{
    protected $fileLocation = WRITEPATH  . '/uploads/';
    protected $title = 'Generate Payroll';
    protected $creator = 'Industri Jamu Dan Farmasi Sido Muncul Tbk';
    
    protected function setProperties(): void
    {
        $this->properties = array(
            'creator' => $this->creator,
            'last_modified_by' => $this->session->get(S_EMPLOYEE_NAME),
            'title' => $this->title,
            'subject' => $this->creator,
            'description' => 'List data of Generate Payroll',
            'keywords' => 'allowance',
            'category' => 'master',
        );
    }

    /**Start Set Header */
    protected function headers()
    {
        return array(
            lang('Shared.label.company'),
            lang('Shared.label.area'),
            lang('Shared.label.role'),
            lang('GeneratePayroll.inquiry.period'),
            lang('GeneratePayroll.inquiry.description'),
            lang('GeneratePayroll.inquiry.total_employee'),
            lang('GeneratePayroll.inquiry.total_deductions'),
            lang('GeneratePayroll.inquiry.total_allowances'),
            lang('GeneratePayroll.inquiry.total_bruto'),
            lang('GeneratePayroll.inquiry.total_netto'),
            lang('Shared.label.created_by'),
            lang('Shared.label.created_at'),
            lang('Shared.label.changed_by'),
            lang('Shared.label.changed_at')
        );
    }

    protected function setHeaderStyles() : void { 
        $styles = array();
        for($i= 0; $i < count($this->headers); $i++){
            $styles[$i] = ['alignment' => Alignment::HORIZONTAL_CENTER, 'font_weight' => true, 'border' => Border::BORDER_THIN];
        }
        $this->headerStyles = $styles;
    }
    /**End Set Header */

    /**Start Set Fields */
    protected function fields(){
        return array(
            'company_name',
            'work_unit_name', 
            'role_name', 
            'payroll_period', 
            'payroll_title', 
            'total_employee', 
            'total_deductions', 
            'total_allowances', 
            'total_bruto', 
            'total_thp', 
            'created_by',
            'created_dt',
            'changed_by',
            'changed_dt',
        );
    }
    
    protected function setFieldStyles() : void {
        $this->contentStyles = array(
            ['alignment' => Alignment::HORIZONTAL_LEFT,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_LEFT,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_LEFT,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_LEFT,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_LEFT,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_RIGHT,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_RIGHT,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_RIGHT,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_RIGHT,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_RIGHT,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_LEFT,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_CENTER,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_LEFT,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_CENTER,'border' => Border::BORDER_THIN],
        );
    }
    /**End Set Header */

    /**Start Set Format Fields */
    protected function formatFields($data)
    {
        return array_map(function($item){
            $item[3] = std_date($item[3], 'Y-m', 'M Y');
            $item[11] = std_date($item[11], 'Y-m-d H:i:s', 'd F Y H:i:s');
            $item[13] = std_date($item[13], 'Y-m-d H:i:s', 'd F Y H:i:s');
            return $item;
        }, $data);
    }
    /**End Set Format Fields */
}