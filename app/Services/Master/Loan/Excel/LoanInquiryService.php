<?php

namespace App\Services\Master\Loan\Excel;

use App\Libraries\SimpleExcelService;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\BorderStyle;

class LoanInquiryService extends SimpleExcelService
{
    protected $fileLocation = WRITEPATH  . '/uploads/';
    protected $title = 'Master Loan';
    protected $creator = 'Industri Jamu Dan Farmasi Sido Muncul Tbk';
    
    protected function setProperties(): void
    {
        $this->properties = array(
            'creator' => $this->creator,
            'last_modified_by' => $this->session->get(S_EMPLOYEE_NAME),
            'title' => $this->title,
            'subject' => $this->creator,
            'description' => 'List data of Master Loan',
            'keywords' => 'loan',
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
            lang('Loan.filter.cost_center'),
            lang('Loan.inquiry.employee_id'),
            lang('Loan.inquiry.employee_name'),
            lang('Loan.inquiry.loan_type'),
            lang('Loan.inquiry.loan_term'),
            lang('Loan.inquiry.loan_amount'),
            lang('Loan.inquiry.monthly_deduction'),
            lang('Loan.inquiry.start_period'),
            lang('Loan.inquiry.end_period'),
            lang('Loan.inquiry.remark'),
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
            'cost_center_desc', 
            'no_reg', 
            'employee_name',
            'loan_type_name',
            'loan_duration_name',
            'loan_total',
            'monthly_deduction',
            'deduction_period_start',
            'deduction_period_end',
            'loan_description',
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
            ['alignment' => Alignment::HORIZONTAL_CENTER,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_CENTER,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_RIGHT,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_RIGHT,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_RIGHT,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_CENTER,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_CENTER,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_LEFT,'border' => Border::BORDER_THIN],
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
            $item[8] = number_format((float)$this->decrypt($item[8]), 0, '.', '.');
            $item[9] = number_format((float)$this->decrypt($item[9]), 0, '.', '.');
            return $item;
        }, $data);
        
    }
}