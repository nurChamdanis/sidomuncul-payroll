<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

/**
 * Lang
 *
 * Fetches a language variable and optionally outputs a form label
 *
 * @access	public
 * @param	string	the language line
 * @param	string	the id of the form element
 * @return	string
 */

// Define the $langArray outside the function
$langArray = [];
function lang($key = '') {
    /**
     * Load Default Locale Language
     */
    if (is_null(get_cookie('lang_code'))) {
        set_cookie('lang_code', 'en', '999600');
    }

    global $langArray; // Access the global $langArray variable

    $language = !empty(get_cookie('lang_code')) ? get_cookie('lang_code') : 'en'; 

    // Check if the language array is empty
    if (empty($langArray)) {
        // List of available languages

        // Path to the language file
        $filePath = APPPATH . 'Language/' . $language . '/app.php';

        // Check if the file exists
        if (file_exists($filePath)) {
            // Include the language file
            $langStrings = include_once $filePath;

            // Merge language strings into the array
            $langArray[$language] = $langStrings;
        }
    }

    $keys = explode('.', $key);
    $temp = isset($langArray[$language]) ? $langArray[$language] : array();
    foreach ($keys as $k) {
        if (isset($temp[$k])) {
            $temp = $temp[$k];
        } else {
            // If key does not exist, return the key itself
            return $key;
        }
    }
    return $temp;
}

/**
 * Function to include all PHP files recursively, excluding App.php
 *
 * @access	public
 * @param	string	the directory to include
 * @param	array	the language array
 */
function includeAllLanguage($dir, &$langArray) {
    // Get all files and directories in the current directory
    $files = glob($dir . '/*');
    
    // Loop through each file and directory
    foreach ($files as $file) {
        // Exclude "." and ".."
        if ($file != '.' && $file != '..') {
            // If it's a directory, recursively call the function
            if (is_dir($file)) {
                includeAllLanguage($file, $langArray);
            } elseif (pathinfo($file, PATHINFO_EXTENSION) == 'php' && basename($file) !== 'app.php') {
                // If it's a PHP file and not 'app.php', include it and merge language strings into the array
                $langStrings = include $file;
                $langArray = array_merge($langArray, $langStrings);
            }
        }
    }
}

/**
 * Function to get All Language Files
 *
 * @access	public
 */
$langArray = [];
function get_all_lang() {
    /**
     * Load Default Locale Language
     */
    if (is_null(get_cookie('lang_code'))) {
        set_cookie('lang_code', 'en', '999600');
    }

    global $tempArray; // Access the global $langArray variable

    $language = !empty(get_cookie('lang_code')) ? strtolower(get_cookie('lang_code')) : 'en'; 
    
    if (empty($tempArray)) {

        // Path to the language file
        $filePath = APPPATH . 'Language/' . $language . '/app.php';

        // Check if the file exists
        if (file_exists($filePath)) {
            // Include the language file
            $langStrings = require $filePath;

            // Merge language strings into the array
            $tempArray[$language] = $langStrings;
        }
    }

    return $tempArray[$language];
}

/**
 *  Function to include all PHP files recursively, excluding ControlRoutes.php
 *
 * @access	public
 */
function includeRouteFiles($directory, $routes) {
    // Open the directory
    if ($handle = opendir($directory)) {
        // Loop through each file in the directory
        while (false !== ($entry = readdir($handle))) {
            // Ignore "." and ".." directories and ControlRoutes.php
            if ($entry != "." && $entry != ".." && $entry != "ControlRoutes.php") {
                // If the entry is a directory, recursively call the function
                if (is_dir($directory . $entry)) {
                    includeRouteFiles($directory . $entry . '/', $routes);
                } elseif (pathinfo($entry, PATHINFO_EXTENSION) == 'php') {
                    // If the entry is a PHP file, include it
                    include_once $directory . $entry;
                }
            }
        }
        // Close the directory handle
        closedir($handle);
    }
}

/**
 *  Function check if data is empty or not
 *
 * @access	public
 */
function isEmpty($val) {
    return !empty($val) ? $val : '-';
}

/**
 *  Function change string to capitalize
 *
 * @access	public
 */
function capitalize($val) {
    return ucwords(strtolower($val));
}

/**
 *  Function change string to lower
 *
 * @access	public
 */
function lower($val) {
    return strtolower($val);
}

/**
 *  Function change string to lower
 *
 * @access	public
 */
function labelDate($val) {
    if(!empty($val) && $val != '-'){
        $data = date('d F Y,H:i:s', strtotime($val));
        $label = explode(",", $data);
        $html = "<div>
            <div class='custom_label'>{$label[0]}</div>
            <div class='small'><mark>{$label[1]}</mark></div>
        </div>";
        return $html;
    } else {
        return "-";
    }
}

if (! function_exists('queryTransaction')) {
    /**
     * Execute a callback function within a database transaction.
     *
     * @param callable $callback The callback function to execute within the transaction.
     * @param mixed    &$error   Reference to an error variable to store any transaction errors.
     * @return mixed Returns the result of the callback function.
     */
    function queryTransaction(callable $callback, &$error = null)
    {
        $db = db_connect();

        $db->transStart();

        try {
            // Call the provided callback function
            $result = $callback();

            // Commit the transaction
            $db->transCommit();

            return $result;
        } catch (\Exception $e) {
            // Rollback the transaction on error
            $db->transRollback();

            // Set the error message
            $error = $e->getMessage();

            return false;
        }
    }
}

if (! function_exists('std_date')) {
    /**
     * Convert a date to the standard format Y-m-d (YYYY-MM-DD).
     *
     * @param string $date The input date to convert.
     * @param string $inputFormat The format of the input date (optional, default: 'd/m/Y').
     * @param string $outputFormat The desired output format (optional, default: 'Y-m-d').
     * @return string The date in the standard format.
     */
    function std_date($date, $inputFormat = 'd/m/Y', $outputFormat = 'Y-m-d')
    {
        // Create a DateTime object from the input date
        $dateTime = \DateTime::createFromFormat($inputFormat, $date);

        // Format the date as per the desired output format
        return $dateTime ? $dateTime->format($outputFormat) : null;
    }
}

/**
 *  Function change string to lower
 *
 * @access	public
 */
function array_exclude($array, $key) {
    return array_diff_key($array, array_flip($key));
}

/**
 *  Function remove all dots
 *
 * @access	public
 */
function decimalvalue($val) {
    return preg_replace('/\./', '', $val); 
}

/**
 *  Function rupiah
 *
 * @access	public
 */
function number($val) {
    return (!empty($val) && $val != "-") ? number_format($val,0,',','.') : 0;
}

/**
 *  Function dump
 *
 * @access	public
 */
function data_dump($data)
{
    echo '<pre>';
    print_r($data);
    exit();
}

if (! function_exists('access_data')) {
    /**
     * Convert a date to the standard format Y-m-d (YYYY-MM-DD).
     *
     * @param string $alias Table alias.
     * @param string $fields Show spesific fields
     * @param string $type Type of output condition: find_in_set, where_in, plain ( default )
     * @return array $condition Add custom condition
     */
    function access_data($alias = '', $fields = '', $type = 'plain', $connector = 'AND', $conditions = array())
    {
        $db = \Config\Database::connect();
        $session = \Config\Services::session();
        $s_employee_noreg = $session->get(S_NO_REG);
        $s_employee_id = $session->get(S_EMPLOYEE_ID);
        $table = 'tb_r_user_group_payroll_employee';

        $defaultCondition = array(
            'session_username' => $s_employee_noreg,
            'session_employee_id' => $s_employee_id,
        );
        if(!empty($conditions)) $defaultCondition = array_merge($defaultCondition, $conditions);
        $existsDataAccess = $db->table($table)->where($defaultCondition)->get()->getResult();

        $sql = "";
        if(!empty($existsDataAccess)){
            /** Employee Id */
            // employee_id
            $employee_id = array_map(function($item){
                return $item->employee_id;
            }, $existsDataAccess);
            // company_id
            $company_id = array_map(function($item){
                return $item->company_id;
            }, $existsDataAccess);
            // work_unit_id
            $work_unit_id = array_map(function($item){
                return $item->work_unit_id;
            }, $existsDataAccess);
            // role_id
            $role_id = array_map(function($item){
                return $item->role_id;
            }, $existsDataAccess);
            // position_id
            $position_id = array_map(function($item){
                return $item->position_id;
            }, $existsDataAccess);

            $inCondition = function($alias, $field, $data) use ($type){
                if(!empty($data)){
                    if($type == 'find_in_set')
                    {
                        if(!empty($alias)):
                            return "(FIND_IN_SET({$alias}.{$field}, '".implode(",", array_unique($data))."'))";
                        else:
                            return "(FIND_IN_SET({$field}, '".implode(",", array_unique($data))."'))"; 
                        endif; 
                    }
                    else if($type == 'where_in')
                    {
                        $whereIn = implode(',', array_unique(array_map(function ($code) {
                            return "'".$code."'";
                        }, $data)));
    
                        if(!empty($alias)):
                            return "({$alias}.{$field} IN ({$whereIn}))";
                        else:
                            return "({$field} IN ({$whereIn}))";
                        endif; 
                    } 
                    else 
                    {
                        return implode(",", array_unique($data));
                    }
                } else {
                    return "";
                }
            };

            $whereEmployees = !empty($employee_id) ? $inCondition($alias, "employee_id",  $employee_id) : "";
            $whereCompanies = !empty($company_id) ? $inCondition($alias, "company_id",  $company_id) : "";
            $whereWorkunits = !empty($work_unit_id) ? $inCondition($alias, "work_unit_id",  $work_unit_id) : "";
            $whereRoles = !empty($role_id) ? $inCondition($alias, "role_id",  $role_id) : "";
            $wherePositions = !empty($position_id) ? $inCondition($alias, "position_id",  $position_id) : "";

            $wheres = array();

            if($type != 'plain')
            {
                if(empty($fields)){
                    if(!empty($whereEmployees)) $wheres[] = $whereEmployees;
                    if(!empty($whereCompanies)) $wheres[] = $whereCompanies;
                    if(!empty($whereWorkunits)) $wheres[] = $whereWorkunits;
                    if(!empty($whereRoles)) $wheres[] = $whereRoles;
                    if(!empty($wherePositions)) $wheres[] = $wherePositions;
                } else {
                    $extractFields = explode(",",$fields);
                    
                    foreach ($extractFields as $key => $value) {
                        if($value == "employee_id"){
                            $wheres[] = $whereEmployees;
                        }
    
                        if($value == "company_id"){
                            $wheres[] = $whereCompanies;
                        }
    
                        if($value == "work_unit_id"){
                            $wheres[] = $whereWorkunits;
                        }
    
                        if($value == "role_id"){
                            $wheres[] = $whereRoles;
                        }
    
                        if($value == "position_id"){
                            $wheres[] = $wherePositions;
                        }
                    }
                }
                $sql .= "(".implode(" {$connector} ", array_values($wheres)).")";
            } else {
                if(empty($fields)){
                    if(!empty($whereEmployees)) $wheres['employee_id'] = $whereEmployees;
                    if(!empty($whereCompanies)) $wheres['company_id'] = $whereCompanies;
                    if(!empty($whereWorkunits)) $wheres['work_unit_id'] = $whereWorkunits;
                    if(!empty($whereRoles)) $wheres['role_id'] = $whereRoles;
                    if(!empty($wherePositions)) $wheres['position_id'] = $wherePositions;
                } else {
                    $extractFields = explode(",",$fields);
                    
                    foreach ($extractFields as $key => $value) {
                        if($value == "employee_id"){
                            $wheres['employee_id'] = $whereEmployees;
                        }
    
                        if($value == "company_id"){
                            $wheres['company_id'] = $whereCompanies;
                        }
    
                        if($value == "work_unit_id"){
                            $wheres['work_unit_id'] = $whereWorkunits;
                        }
    
                        if($value == "role_id"){
                            $wheres['role_id'] = $whereRoles;
                        }
    
                        if($value == "position_id"){
                            $wheres['position_id'] = $wherePositions;
                        }
                    }
                }

                return $wheres;
            }
        }
        
        return $sql;
    }
}


if (! function_exists('decryptString')) {
    function decryptString($field)
    {
        $session = \Config\Services::session();
        $SIDOKEY = $session->get(SIDOKEY);
        $key = HRISSIDO2024;
        return "decrypt_data({$field}, decrypt_data('{$SIDOKEY}', '{$key}'))";
    }
}