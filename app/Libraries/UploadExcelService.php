<?php

namespace App\Libraries;

use App\Libraries\DownloadExcelService;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UploadExcelService
{
    protected $fileLocation;
    protected $spreadsheet;
    protected $startRowIndex = 4;
    protected $currentRowIndex = 1;
    
    public function readExcel($file)
    {
        $data = [];
        
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            
            if (!is_dir($this->fileLocation)) {
                // Directory does not exist, so create it
                if (!mkdir($this->fileLocation, 0755, true)) {
                    // If directory creation fails, show an error message
                    echo "Failed to create directory: $this->fileLocation";
                    return false;
                }
            }

            $file->move($this->fileLocation, $newName);
            $this->spreadsheet = IOFactory::load($this->fileLocation . $newName);
            $sheet = $this->spreadsheet->getActiveSheet();

            $startRowIndex = $this->startRowIndex; 
            $currentRowIndex = $this->currentRowIndex;

            foreach ($sheet->getRowIterator() as $row) {
                if ($currentRowIndex < $startRowIndex) {
                    $currentRowIndex++;
                    continue;
                }

                $rowData = [];
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                
                foreach ($cellIterator as $cell) {
                    $rowData[] = $cell->getValue();
                }

                $data[] = $rowData;
                $currentRowIndex++;
            }
        } else {
            throw new Exception("Error Processing Request");
        }

        return $data;
    }
}