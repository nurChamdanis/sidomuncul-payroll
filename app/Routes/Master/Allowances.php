<?php

/**
 * Master Allowances
 */

$routes->group('master_tunjangan', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Master\AllowancesController::index', ['as' => 'allowance']);
    $routes->get('create', 'Master\AllowancesController::create', ['as' => 'allowance.create']);
    $routes->get('id/(:segment)', 'Master\AllowancesController::id/$1', ['as' => 'allowance.id']);
    $routes->get('edit/(:segment)', 'Master\AllowancesController::edit/$1', ['as' => 'allowance.edit']);
    $routes->get('upload', 'Master\AllowancesController::index_upload', ['as' => 'allowance.index_upload']);

    $routes->post('datatable', 'Master\AllowancesController::getDataTable', ['as' => 'allowance.datatable']);
    $routes->post('store', 'Master\AllowancesController::actionCreate', ['as' => 'allowance.store']);
    $routes->post('update', 'Master\AllowancesController::actionUpdate', ['as' => 'allowance.update']);
    $routes->post('remove', 'Master\AllowancesController::actionRemove', ['as' => 'allowance.remove']);
    $routes->post('removeSelected', 'Master\AllowancesController::actionRemoveSelected', ['as' => 'allowance.remove_selected']);
    
    $routes->post('export/excel', 'Master\AllowancesController::actionDownloadExcel', ['as' => 'allowance.download_excel']);
    $routes->post('datatable_uploaded', 'Master\AllowancesController::getUploadDataTable', ['as' => 'allowance.datatable_uploaded']);
    $routes->post('download_template/excel', 'Master\AllowancesController::actionDownloadExcelTemplate', ['as' => 'allowance.download_excel_template']);
    $routes->post('upload_template/read', 'Master\AllowancesController::actionUploadExcel', ['as' => 'allowance.upload_excel']);
    $routes->post('upload_template/create', 'Master\AllowancesController::actionSubmitExcelTemplate', ['as' => 'allowance.process_upload_excel']);
    $routes->post('upload_template/get-invalid', 'Master\AllowancesController::getInvalidData', ['as' => 'allowance.uoload_get_invalid']);

    /**
     * List & Options Form
     */
    $routes->get('area', 'Master\AllowancesController::getAllArea', ['as' => 'allowance.area']);
    $routes->get('area_grup', 'Master\AllowancesController::getAllAreaGrup', ['as' => 'allowance.area_grup']);
    $routes->get('payroll_rules', 'Master\AllowancesController::getAllPayrollRules', ['as' => 'allowance.payroll_rules']);
    $routes->get('options/company', 'Master\AllowancesController::getOptionsCompany', ['as' => 'allowance.company_options']);
    $routes->get('options/area', 'Master\AllowancesController::getOptionsArea', ['as' => 'allowance.area_options']);
    $routes->get('options/areagroup', 'Master\AllowancesController::getOptionsAreaGroup', ['as' => 'allowance.areagroup_options']);
    $routes->get('options/glaccount', 'Master\AllowancesController::getOptionsGLAccount', ['as' => 'allowance.glaccount_options']);
});
