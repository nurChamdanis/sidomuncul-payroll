<?php

namespace App\Services\Master\Compensation;

/** 
 * @author fernanda.rizqi@arkamaya.co.id
 * @since May 2024
 */

use App\Libraries\SimpleExcelService;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\BorderStyle;

use function App\Services\Master\labelDateCustom;

class CompensationDownloadService extends SimpleExcelService
{
    protected $fileLocation = WRITEPATH . '/uploads/';
    protected $title = 'Master Compensation';
    protected $creator = 'Industri Jamu Dan Farmasi Sido Muncul Tbk';

    protected function setProperties(): void
    {
        $this->properties = array(
            'creator' => $this->creator,
            'last_modified_by' => $this->session->get(S_EMPLOYEE_NAME),
            'title' => $this->title,
            'subject' => $this->creator,
            'description' => 'List data of Master Compensation',
            'keywords' => 'compensation',
            'category' => 'master',
        );
    }
    /**Start Set Header */
    protected function headers()
    {
        return array(
            lang('Shared.label.company'),
            lang('Shared.label.area'),
            lang('Compensation.inquiry.work_unit'),
            lang('Compensation.inquiry.employee_number'),
            lang('Compensation.inquiry.employee_name'),
            lang('Compensation.inquiry.period'),
            lang('Compensation.inquiry.compensation_type'),
            lang('Compensation.inquiry.total_compensation'),
            lang('Shared.label.created_by'),
            lang('Shared.label.created_at'),
            lang('Shared.label.changed_by'),
            // lang('Shared.label.changed_at')
        );
    }

    protected function setHeaderStyles(): void
    {
        $styles = array();
        for ($i = 0; $i < count($this->headers); $i++) {
            $styles[$i] = ['alignment' => Alignment::HORIZONTAL_CENTER, 'font_weight' => true, 'border' => Border::BORDER_THIN];
        }
        $this->headerStyles = $styles;
    }
    /**End Set Header */

    /**Start Set Fields */

    protected function fields()
    {
        return array(
            'company_name',
            'name',
            'role_name',
            'no_reg',
            'employee_name',
            'period',
            'system_value_txt',
            'total_compensation',
            'created_by',
            'created_dt',
            'changed_by'
        );
    }

    protected function setFieldStyles() : void {
        $this->contentStyles = array(
            ['alignment' => Alignment::HORIZONTAL_CENTER,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_CENTER,'border' => Border::BORDER_THIN],
            ['alignment' => Alignment::HORIZONTAL_CENTER,'border' => Border::BORDER_THIN],
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

    function labelDateCustom($val)
    {
        if (!empty($val) && $val != '-') {

            list($year, $month) = explode('-', $val);

            if (is_numeric($month) && is_numeric($year) && $month >= 1 && $month <= 12) {

                $timestamp = mktime(0, 0, 0, $month, 1, $year);

                $data = date('F Y', $timestamp);

                return $data;
            } else {
                return "Invalid date format";
            }
        } else {
            return "-";
        }
    }


    /**End Set Header */
    protected function formatFields($data)
    {
        return array_map(function($item){
            $item[5] = $this->labelDateCustom($item[5]);
            $item[7] = $this->decrypt($item[7]);
            $item[9] = std_date($item[9], 'Y-m-d H:i:s', 'd F Y H:i:s');
            $item[10] = std_date($item[10], 'Y-m-d H:i:s', 'd F Y H:i:s');
            return $item;
        }, $data);
        
    }
}
