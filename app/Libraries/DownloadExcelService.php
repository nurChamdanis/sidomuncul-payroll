<?php

namespace App\Libraries;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DownloadExcelService
{
    protected $fileLocation;
    protected $spreadsheet;
    protected $fileName;
    
    protected $title;
    protected $creator;
    protected $subject;
    protected $description;
    protected $keywords;
    protected $category;
    
    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
        $this->setDocumentProperties();
    }
    
    /**
     * @return this instance
     * ----------------------------------------------------
     * name : setFileName()
     * desc : Service to set file name
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }
    
    /**
     * @return string $filePaths
     * ----------------------------------------------------
     * name : fillData(spreadsheet,fileName)
     * desc : Service to write data
     */
    protected function writeToFile(): string
    {
        $writer = new Xlsx($this->spreadsheet);

        if (!is_dir($this->fileLocation)) {
            // Directory does not exist, so create it
            if (!mkdir($this->fileLocation, 0755, true)) {
                // If directory creation fails, show an error message
                echo "Failed to create directory: $this->fileLocation";
                return false;
            }
        }

        $filePath = $this->fileLocation . $this->fileName;
        $writer->save($filePath);

        return $filePath;
    }
    
    /**
     * @var $sheet
     * @var $cell
     * @var $size
     * @return void
     * ----------------------------------------------------
     * name : setFontSize(sheet,cell,$type,$alignment)
     * desc : Set Cell Font Size
     */
    protected function setFontSize($sheet, $cell, $size)
    {
        $sheet->getStyle($cell)->getFont()->setSize($size);
    }

    /**
     * @var $sheet
     * @var $cell
     * @var $type
     * @var $alignment
     * @return void
     * ----------------------------------------------------
     * name : setAlignment(sheet,cell,$type,$alignment)
     * desc : Set Cell Alignment
     */
    protected function setAlignment($sheet, $cell, $type, $alignment)
    {
        if($type == 'horizontal'){
            $sheet->getStyle($cell)->getAlignment()->setHorizontal($alignment);
        } else {
            $sheet->getStyle($cell)->getAlignment()->setVertical($alignment);
        }
    }
    
    /**
     * @var $sheet
     * @var $cell
     * @var $bold
     * @return void
     * ----------------------------------------------------
     * name : setBold(sheet,cell,$bold)
     * desc : Set Cell Font Bold
     */
    protected function setBold($sheet, $cell, $bold)
    {
        $sheet->getStyle($cell)->getFont()->setBold($bold);
    }

    /**
     * @var $sheet
     * @var $cell
     * @return void
     * ----------------------------------------------------
     * name : setBorder(sheet,cell)
     * desc : Set Cell Border
     */
    protected function setBorder($sheet, $cell)
    {
        $sheet->getStyle($cell)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    }

    /**
     * @var $sheet
     * @var $cell
     * @var $background
     * @return void
     * ----------------------------------------------------
     * name : setBackground(sheet,cell,background)
     * desc : Set Cell Background Color
     */
    protected function setBackground($sheet, $cell, $background)
    {
        $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($background);
    }

    /**
     * @var Spreadhseet $spreadsheet
     * @var array $properties
     * @return void
     * ----------------------------------------------------
     * name : setDocumentProperties(spreadsheet,properties)
     * desc : Service to set Document Properties
     */
    private function setDocumentProperties(): void
    {
        $this->spreadsheet->getProperties()
            ->setCreator($this->title)
            ->setLastModifiedBy($this->creator)
            ->setTitle($this->title)
            ->setSubject($this->subject)
            ->setDescription($this->description)
            ->setKeywords($this->keywords)
            ->setCategory($this->category);
    }

    
}