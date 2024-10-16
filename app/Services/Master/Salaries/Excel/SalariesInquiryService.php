<?php

namespace App\Services\Master\Salaries\Excel;

use App\Libraries\SimpleExcelService;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\BorderStyle;

class SalariesInquiryService extends SimpleExcelService
{
    protected $fileLocation = WRITEPATH  . '/uploads/';
    protected $title = 'Master Salaries';
    protected $creator = 'Industri Jamu Dan Farmasi Sido Muncul Tbk';
    
    protected function setProperties(): void
    {
        $this->properties = array(
            'creator' => $this->creator,
            'last_modified_by' => $this->session->get(S_EMPLOYEE_NAME),
            'title' => $this->title,
            'subject' => $this->creator,
            'description' => 'List data of Master Salaries',
            'keywords' => 'salaries',
            'category' => 'master',
        );
    }

    /**Start Set Header */
    protected function headers()
    {
        return array(
            lang('Shared.label.company'),
            lang('Shared.label.area'),
            lang('Loan.filter.role'),
            lang('Loan.inquiry.employee_id'),
            lang('Loan.inquiry.employee_name'),
            lang('Salaries.inquiry.basic_salary'),
            lang('Salaries.inquiry.dedduction'),
            lang('Salaries.inquiry.allowance'),
            lang('Salaries.inquiry.THP'),
            lang('Salaries.inquiry.effective_date'),
            lang('Salaries.inquiry.effective_date') . ' ' . lang('Salaries.inquiry.bpjs'),
            lang('Shared.label.created_by'),
            lang('Shared.label.created_at'),
            lang('Shared.label.changed_by'),
            lang('Shared.label.changed_at'),
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
            'no_reg', 
            'employee_name',
            'basic_salary',
            'total_deduction_no_tax',
            'total_allowance_no_tax',
            'thp_estimation',
            'effective_date_start',
            'effective_date_bpjs',
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
            ['alignment' => Alignment::HORIZONTAL_CENTER,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_CENTER,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_RIGHT,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_RIGHT,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_RIGHT,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_RIGHT,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_CENTER,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_CENTER,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_CENTER,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_CENTER,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_CENTER,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_CENTER,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_CENTER,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_CENTER,'border' => Border::BORDER_THIN],
        );
    }
    /**End Set Header */

    protected function formatFields($data)
    {
        return array_map(function($item){
            $item[5] = number($this->decrypt($item[5]));
            $item[6] = number($this->decrypt($item[6]));
            $item[7] = number($this->decrypt($item[7]));
            $item[8] = number($this->decrypt($item[8]));
            return $item;
        }, $data);
        
    }
}