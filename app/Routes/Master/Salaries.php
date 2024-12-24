<?php

/**
 * Master Salaries  Routes
 */

$routes->group('master_salaries', ['filter' => 'auth'], function ($routes) {
    // page request
    $routes->get('/', 'Master\SalariesController::index', ['as' => 'salaries']);
    $routes->get('create', 'Master\SalariesController::create', ['as' => 'salaries.create']);
    $routes->get('id/(:segment)', 'Master\SalariesController::id/$1', ['as' => 'salaries.id']);
    $routes->get('edit/(:segment)', 'Master\SalariesController::edit/$1', ['as' => 'salaries.edit']);
    // $routes->get('upload', 'Master\SalariesController::index_upload', ['as' => 'salaries.index_upload']);

    //master routes
    $routes->post('datatable', 'Master\SalariesController::getDataTable', ['as' => 'salaries.datatable']);
    $routes->post('remove', 'Master\SalariesController::actionRemove', ['as' => 'salaries.remove']);
    $routes->post('removeSelected', 'Master\SalariesController::actionRemoveSelected', ['as' => 'salaries.remove_selected']);
    $routes->post('store', 'Master\SalariesController::actionCreate', ['as' => 'salaries.store']);
    $routes->post('update', 'Master\SalariesController::actionUpdate', ['as' => 'salaries.update']);
    $routes->post('getDetailAllowancesDeductions', 'Master\SalariesController::getDetailAllowancesDeductions', ['as' => 'salaries.detailAllowancesDeductions']);
    $routes->post('getDetailAllowancesDeductionsSalary', 'Master\SalariesController::getDetailAllowancesDeductionsSalary', ['as' => 'salaries.detailAllowancesDeductionsSalary']);

    // download routes
    $routes->post('export/excel', 'Master\SalariesController::actionDownloadExcel', ['as' => 'salaries.download_excel']);

    // upload routes
    // $routes->post('datatable_uploaded', 'Master\SalariesController::getUploadDataTable', ['as' => 'salaries.datatable_uploaded']);
    // $routes->post('download_template/excel', 'Master\SalariesController::actionDownloadExcelTemplate', ['as' => 'salaries.download_excel_template']);
    // $routes->post('upload_template/read', 'Master\SalariesController::actionUploadExcel', ['as' => 'salaries.upload_excel']);
    // $routes->post('upload_template/create', 'Master\SalariesController::actionSubmitExcelTemplate', ['as' => 'salaries.process_upload_excel']);
    // $routes->post('upload_template/get-invalid', 'Master\SalariesController::getInvalidData', ['as' => 'salaries.upload_get_invalid']);

    // options routes
    $routes->get('options/company', 'Master\SalariesController::getOptionsCompany', ['as' => 'salaries.company_options']);
    $routes->get('options/area', 'Master\SalariesController::getOptionsArea', ['as' => 'salaries.area_options']);
    $routes->get('options/role', 'Master\SalariesController::getOptionsRole', ['as' => 'salaries.role_options']);
    $routes->get('options/employee', 'Master\SalariesController::getOptionsEmployee', ['as' => 'salaries.employee_options']);
    $routes->get('options/cost_center', 'Master\SalariesController::getOptionsCostCenter', ['as' => 'salaries.cost_center_options']);
    $routes->get('options/system', 'Master\SalariesController::getOptionsSytem', ['as' => 'salaries.system_options']);
    $routes->get('options/contract_type', 'Master\SalariesController::getOptionsEmployeeContract', ['as' => 'salaries.employee_contract_options']);
});
