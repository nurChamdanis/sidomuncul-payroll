<?php

namespace App\Libraries;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SimpleExcelService
{
    protected $spreadsheet;
    protected $fileName;
    protected $title = '';
    protected $properties = [];
    protected $fileLocation = '';
    protected $secretKey;
    protected $session;
    protected $encryption;
    protected $headerStyles = [];
    protected $contentStyles = [];
    protected $headers;
    protected $data;
    public $fields = [];
    public $columnIndex = 0;
    public $rowIndex = 5;

    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
        $this->fileName = 'Default.xlsx';
        $this->encryption = new EncryptionLib(HRISSIDO2024);
        $this->session = session();
        
        $this->secretKey = $this->getSecretKey();
        $this->setProperties();
        $this->setHeaders();
        $this->setHeaderStyles();
        $this->setFields();
        $this->setFieldStyles();
    }

    /**
     * @return string decyryption value
     * ----------------------------------------------------
     * name : getSecretKey()
     * desc : Service to decryption value
     */
    protected function getSecretKey()
    {
        if ($this->session->get(SIDOKEY)) {
            return $this->encryption->decryptData($this->session->get(SIDOKEY));
        }

        $db = \Config\Database::connect();
        $encryptionKey = $db->table('tb_m_system')->where('system_type', SIDOKEY)->get()->getRow();
        return $this->encryption->decryptData($encryptionKey->system_value_txt ?? '');
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
     * @var string $name
     * @return this instance
     * ----------------------------------------------------
     * name : setWorksheetName(name)
     * desc : Service to set worksheet name
     */
    public function setWorksheetName($name)
    {
        $this->spreadsheet->getActiveSheet()->setTitle($name);
        return $this;
    }
    
    /**
     * @return void
     * ----------------------------------------------------
     * name : setProperties(name)
     * desc : Service to set properties
     */
    protected function setProperties(): void{}

    /**
     * @return this instance
     * ----------------------------------------------------
     * name : setHeaders() & headers()
     * desc : Service to set headers
     */
    protected function setHeaders()
    {
        if(!empty($this->headers())){
            $this->headers = $this->headers();
        }
        return $this;
    }

    protected function headers()
    {
        return [];
    }
    
    /**
     * @return this instance
     * ----------------------------------------------------
     * name : formatFields()
     * desc : Service to format fields
     */
    protected function formatFields($data)
    {
        return !empty($data) ? $data : array();
    }
    
    /**
     * @return void
     * ----------------------------------------------------
     * name : setHeaderStyles()
     * desc : Service to format fields
     */
    protected function setHeaderStyles() : void {}
    
    
    /**
     * @return this instance
     * ----------------------------------------------------
     * name : setFields() & fields()
     * desc : Service to set fields
     */
    protected function setFields()
    {
        if(!empty($this->fields())){
            $this->fields = $this->fields();
        }
        return $this;
    }

    protected function fields()
    {
        return [];
    }

    /**
     * @return void
     * ----------------------------------------------------
     * name : setFieldStyles()
     * desc : Service to set fields styles
     */
    protected function setFieldStyles() : void {}
    
    /**
     * @var callable $callback
     * @return void
     * ----------------------------------------------------
     * name : setData(callback)
     * desc : Service to set data
     */
    protected function setData($callback)
    {
        $data = is_callable($callback) ? $callback() : $callback;
        $this->data = array_merge([$this->headers], $data ?: []);

        return $this;
    }

    /**
     * @var array $data
     * @return void
     * ----------------------------------------------------
     * name : generate(data)
     * desc : Service to generate excel
     */
    public function generate(array $data): string
    {
        $sheet = $this->spreadsheet->getActiveSheet();

        $this->setDocumentProperties($this->spreadsheet, $this->properties);
        $this->setData($this->formatFields($data));
        $this->fillData($sheet, $this->data, $this->headerStyles, $this->contentStyles);

        return $this->writeToFile($this->spreadsheet, $this->fileName);
    }

    /**
     * @var string $fileLocation
     * @return void
     * ----------------------------------------------------
     * name : setLocation(fileLocation)
     * desc : Service to set fileLocation
     */
    protected function setLocation(string $fileLocation)
    {
        $this->fileLocation = $fileLocation;
        return $this;
    }

    /**
     * @var Spredseet $spreadsheet
     * @var array $properties
     * @return void
     * ----------------------------------------------------
     * name : setDocumentProperties(spreadsheet,properties)
     * desc : Service to set Document Properties
     */
    private function setDocumentProperties(Spreadsheet $spreadsheet, array $properties): void
    {
        $spreadsheet->getProperties()
            ->setCreator($properties['creator'] ?? 'Unknown')
            ->setLastModifiedBy($properties['last_modified_by'] ?? 'Unknown')
            ->setTitle($properties['title'] ?? 'Untitled')
            ->setSubject($properties['subject'] ?? 'Subject')
            ->setDescription($properties['description'] ?? 'Description')
            ->setKeywords($properties['keywords'] ?? 'keywords')
            ->setCategory($properties['category'] ?? 'Category');
    }

    /**
     * @var Spredseet $sheet
     * @var string $title
     * @var int $row
     * @var int $column
     * @var int $numColumns
     * @return void
     * ----------------------------------------------------
     * name : setTitle(sheet,title,row,column,numColumns)
     * desc : Service to set Title File
     */
    protected function setTitle($sheet, string $title, int $row, int $column, int $numColumns): void
    {
        // Merge cells for the title row
        $columnLetter = $this->getColumnLetter($column);
        $sheet->mergeCells("{$columnLetter}{$row}:" . $this->getColumnLetter($numColumns - 1) . $row);

        // Set the title in the merged cell
        $titleCell = $columnLetter . $row;
        $sheet->setCellValue($titleCell, $title);

        // Center align and apply bold style to the title
        $sheet->getStyle($titleCell)
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $sheet->getStyle($titleCell)
            ->getFont()
            ->setBold(true);
    }

    /**
     * @var Spredseet $sheet
     * @var array $data
     * @var array $headerStyles
     * @var array $contentStyles
     * @return void
     * ----------------------------------------------------
     * name : fillData(sheet,data,headerStyles,contentStyles)
     * desc : Service to fill Data and Styling
     */
    protected function fillData($sheet, array $data, array $headerStyles, array $contentStyles): void
    {
        $rowIndex = $this->rowIndex;

        if(!empty($this->title)){
            if(($this->rowIndex - 2) > 0){
                $this->setTitle($sheet, $this->title, ($this->rowIndex - 2), $this->columnIndex, count($this->headers));
            }
        }

        foreach ($data as $row) {
            $columnIndex = $this->columnIndex;
            $i = 0;
            foreach ($row as $cell) {
                $columnLetter = $this->getColumnLetter($columnIndex);
                $cellCoordinate = $columnLetter . $rowIndex;

                $sheet->setCellValue($cellCoordinate, $cell);

                // Apply header styles
                if ($rowIndex == $this->rowIndex && isset($headerStyles[$i])) {
                    $this->applyStyles($sheet, $cellCoordinate, $headerStyles[$i]);
                }

                // Apply content styles
                if ($rowIndex > $this->rowIndex && isset($contentStyles[$i])) {
                    $this->applyStyles($sheet, $cellCoordinate, $contentStyles[$i]);
                }

                $columnIndex++;
                $i++;
            }
            $rowIndex++;
        }
        
        foreach(range('A',$sheet->getHighestDataColumn()) as $columnID){
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    }

    /**
     * @var Spredseet $sheet
     * @var string $fileName
     * @return string $filePaths
     * ----------------------------------------------------
     * name : fillData(spreadsheet,fileName)
     * desc : Service to write data
     */
    private function writeToFile(Spreadsheet $spreadsheet, string $fileName): string
    {
        if (!is_dir($this->fileLocation)) {
            // Directory does not exist, so create it
            if (!mkdir($this->fileLocation, 0755, true)) {
                // If directory creation fails, show an error message
                echo "Failed to create directory: $this->fileLocation";
                return false;
            }
        }
        
        $writer = new Xlsx($spreadsheet);
        $filePath = $this->fileLocation . $fileName;
        $writer->save($filePath);

        return $filePath;
    }

    /**
     * @var Spredseet $sheet
     * @var string $cell
     * @return string $style
     * ----------------------------------------------------
     * name : applyStyles(sheet,cell,style)
     * desc : Service to apply styles to cell
     */
    private function applyStyles($sheet, $cell, $style): void
    {
        if (isset($style['alignment'])) {
            $sheet->getStyle($cell)->getAlignment()->setHorizontal($style['alignment']);
        }
        if (isset($style['font_weight'])) {
            $sheet->getStyle($cell)->getFont()->setBold($style['font_weight']);
        }
        if (isset($style['font_style'])) {
            $sheet->getStyle($cell)->getFont()->setItalic($style['font_style']);
        }
        if (isset($style['font_size'])) {
            $sheet->getStyle($cell)->getFont()->setSize($style['font_size']);
        }
        if (isset($style['border'])) {
            $sheet->getStyle($cell)->getBorders()->getAllBorders()->setBorderStyle($style['border']);
        }
    }

    /**
     * @var int $columnIndex
     * @return Coordinate String (A/B/C)
     * ----------------------------------------------------
     * name : getColumnLetter(colIndex)
     * desc : Service to get Column Letter
     */
    private function getColumnLetter(int $colIndex): string
    {
        return \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
    }

    /**
     * @var string $data
     * @return string Encryption Value
     * ----------------------------------------------------
     * name : encrypt(data)
     * desc : Service to encryption data
     */
    public function encrypt($data)
    {
        return $this->encryption->encryptData($data, $this->secretKey);
    }

    /**
     * @var string $data
     * @return string Decryption Value
     * ----------------------------------------------------
     * name : encrypt(data)
     * desc : Service to decryprtion data
     */
    public function decrypt($data)
    {
        return $this->encryption->decryptData($data, $this->secretKey);
    }
}
