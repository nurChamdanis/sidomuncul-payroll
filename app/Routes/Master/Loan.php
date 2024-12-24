<?php

/**
 * Master Loan  Routes
 */

$routes->group('master_loan', ['filter' => 'auth'], function ($routes) {
    // page request
    $routes->get('/', 'Master\LoanController::index', ['as' => 'loan']);
    $routes->get('create', 'Master\LoanController::create', ['as' => 'loan.create']);
    $routes->get('id/(:segment)', 'Master\LoanController::id/$1', ['as' => 'loan.id']);
    $routes->get('edit/(:segment)', 'Master\LoanController::edit/$1', ['as' => 'loan.edit']);
    $routes->get('upload', 'Master\LoanController::index_upload', ['as' => 'loan.index_upload']);

    //master routes
    $routes->post('datatable', 'Master\LoanController::getDataTable', ['as' => 'loan.datatable']);
    $routes->post('remove', 'Master\LoanController::actionRemove', ['as' => 'loan.remove']);
    $routes->post('removeSelected', 'Master\LoanController::actionRemoveSelected', ['as' => 'loan.remove_selected']);
    $routes->post('store', 'Master\LoanController::actionCreate', ['as' => 'loan.store']);
    $routes->post('update', 'Master\LoanController::actionUpdate', ['as' => 'loan.update']);

    // download routes
    $routes->post('export/excel', 'Master\LoanController::actionDownloadExcel', ['as' => 'loan.download_excel']);

    // upload routes
    $routes->post('datatable_uploaded', 'Master\LoanController::getUploadDataTable', ['as' => 'loan.datatable_uploaded']);
    $routes->post('download_template/excel', 'Master\LoanController::actionDownloadExcelTemplate', ['as' => 'loan.download_excel_template']);
    $routes->post('upload_template/read', 'Master\LoanController::actionUploadExcel', ['as' => 'loan.upload_excel']);
    $routes->post('upload_template/create', 'Master\LoanController::actionSubmitExcelTemplate', ['as' => 'loan.process_upload_excel']);
    $routes->post('upload_template/get-invalid', 'Master\LoanController::getInvalidData', ['as' => 'loan.upload_get_invalid']);

    // options routes
    $routes->get('options/company', 'Master\LoanController::getOptionsCompany', ['as' => 'loan.company_options']);
    $routes->get('options/area', 'Master\LoanController::getOptionsArea', ['as' => 'loan.area_options']);
    $routes->get('options/role', 'Master\LoanController::getOptionsRole', ['as' => 'loan.role_options']);
    $routes->get('options/employee', 'Master\LoanController::getOptionsEmployee', ['as' => 'loan.employee_options']);
    $routes->get('options/cost_center', 'Master\LoanController::getOptionsCostCenter', ['as' => 'loan.cost_center_options']);
    $routes->get('options/system', 'Master\LoanController::getOptionsSytem', ['as' => 'loan.system_options']);
});
