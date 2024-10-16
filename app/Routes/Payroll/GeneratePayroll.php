<?php

/**
 * Generate Payroll
 */

$routes->group('generate_payroll', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Payroll\GeneratePayrollController::index', ['as' => 'generate_payroll']);
    $routes->get('generate', 'Payroll\GeneratePayrollController::generate', ['as' => 'generate_payroll.generate']);
    
    $routes->post('store', 'Payroll\GeneratePayrollController::actionCreate', ['as' => 'generate_payroll.store']);
    
    $routes->post('datatable', 'Payroll\GeneratePayrollController::getDataTable', ['as' => 'generate_payroll.datatable']);
    $routes->post('datatable_employeelist', 'Payroll\GeneratePayrollController::getEmployeeListDataTable', ['as' => 'generate_payroll.datatable_employeelist']);
    $routes->post('getallemployee_checkall', 'Payroll\GeneratePayrollController::getAllEmployeeCheckAll', ['as' => 'generate_payroll.getallemployee_checkall']);

    $routes->post('export/excel', 'Payroll\GeneratePayrollController::actionDownloadExcel', ['as' => 'generate_payroll.download_excel']);
    /**
     * List & Options Form
     */
    $routes->group('options',function ($routes) {
        $routes->get('company', 'Payroll\GeneratePayrollController::getOptionsCompany', ['as' => 'generate_payroll.company']);
        $routes->get('area', 'Payroll\GeneratePayrollController::getOptionsArea', ['as' => 'generate_payroll.area']);
        $routes->get('role', 'Payroll\GeneratePayrollController::getOptionsRole', ['as' => 'generate_payroll.role']);
        $routes->get('employee', 'Payroll\GeneratePayrollController::getOptionsEmployee', ['as' => 'generate_payroll.employee']);
    });
});