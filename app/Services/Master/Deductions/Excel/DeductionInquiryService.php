<?php

namespace App\Services\Master\Deductions\Excel;

use App\Libraries\SimpleExcelService;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\BorderStyle;

class DeductionInquiryService extends SimpleExcelService
{
    protected $fileLocation = WRITEPATH  . '/uploads/';
    protected $title = 'Master Deduction';
    protected $creator = 'Industri Jamu Dan Farmasi Sido Muncul Tbk';
    
    protected function setProperties(): void
    {
        $this->properties = array(
            'creator' => $this->creator,
            'last_modified_by' => $this->session->get(S_EMPLOYEE_NAME),
            'title' => $this->title,
            'subject' => $this->creator,
            'description' => 'List data of Master Deduction',
            'keywords' => 'deduction',
            'category' => 'master',
        );
    }

    /**Start Set Header */
    protected function headers()
    {
        return array(
            lang('Shared.label.company'),
            lang('Shared.label.area'),
            lang('Shared.label.group'),
            lang('Deductions.inquiry.effective_date'),
            lang('Deductions.inquiry._name'),
            lang('Deductions.inquiry.default_value'),
            lang('Deductions.inquiry.is_active'),
            lang('Shared.label.created_by'),
            lang('Shared.label.changed_by'),
            lang('Shared.label.created_at'),
            lang('Shared.label.changed_at')
        );
    }

    protected function setHeaderStyles() : void { 
        $styles = array();
        for($i= 0; $i < count($this->headers); $i++){
            $styles[$i] = ['alignment' => Alignment::HORIZONTAL_LEFT, 'font_weight' => true, 'border' => Border::BORDER_THIN];
        }
        $this->headerStyles = $styles;
    }
    /**End Set Header */

    /**Start Set Fields */
    protected function fields(){
        return array(
            'company_name',
            'area_name', 
            'group_name', 
            'effective_date', 
            'deduction_name', 
            'default_value',
            'is_active',
            'created_by',
            'changed_by',
            'created_dt',
            'changed_dt',
        );
    }
    
    protected function setFieldStyles() : void {
        $this->contentStyles = array(
            ['alignment' => Alignment::HORIZONTAL_LEFT,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_LEFT,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_LEFT,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_CENTER,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_LEFT,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_RIGHT,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_CENTER,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_LEFT,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_LEFT,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_CENTER,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_CENTER,'border' => Border::BORDER_THIN],
        );
    }
    /**End Set Header */
    
    protected function formatFields($data)
    {
        return array_map(function($item){
            $item[1] = $item[1];
            $item[5] = $this->decrypt($item[5]);
            $item[6] = $item[6] == "1" ? "Yes" : "No";
            $item[3] = std_date($item[3], 'Y-m-d', 'd F Y');
            $item[9] = std_date($item[9], 'Y-m-d H:i:s', 'd F Y H:i:s');
            $item[10] = std_date($item[10], 'Y-m-d H:i:s', 'd F Y H:i:s');
            return $item;
        }, $data);
        
    }
}