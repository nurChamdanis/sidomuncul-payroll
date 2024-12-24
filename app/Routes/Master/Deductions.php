<?php

/**
 * Master Allowances
 */

$routes->group('master_potongan', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Master\DeductionsController::index', ['as' => 'deduction']);
    $routes->get('create', 'Master\DeductionsController::create', ['as' => 'deduction.create']);
    $routes->get('id/(:segment)', 'Master\DeductionsController::id/$1', ['as' => 'deduction.id']);
    $routes->get('edit/(:segment)', 'Master\DeductionsController::edit/$1', ['as' => 'deduction.edit']);
    $routes->get('upload', 'Master\DeductionsController::index_upload', ['as' => 'deduction.index_upload']);

    $routes->post('datatable', 'Master\DeductionsController::getDataTable', ['as' => 'deduction.datatable']);
    $routes->post('store', 'Master\DeductionsController::actionCreate', ['as' => 'deduction.store']);
    $routes->post('update', 'Master\DeductionsController::actionUpdate', ['as' => 'deduction.update']);
    $routes->post('remove', 'Master\DeductionsController::actionRemove', ['as' => 'deduction.remove']);
    $routes->post('removeSelected', 'Master\DeductionsController::actionRemoveSelected', ['as' => 'deduction.remove_selected']);
    
    $routes->post('export/excel', 'Master\DeductionsController::actionDownloadExcel', ['as' => 'deduction.download_excel']);
    $routes->post('datatable_uploaded', 'Master\DeductionsController::getUploadDataTable', ['as' => 'deduction.datatable_uploaded']);
    $routes->post('download_template/excel', 'Master\DeductionsController::actionDownloadExcelTemplate', ['as' => 'deduction.download_excel_template']);
    $routes->post('upload_template/read', 'Master\DeductionsController::actionUploadExcel', ['as' => 'deduction.upload_excel']);
    $routes->post('upload_template/create', 'Master\DeductionsController::actionSubmitExcelTemplate', ['as' => 'deduction.process_upload_excel']);
    $routes->post('upload_template/get-invalid', 'Master\DeductionsController::getInvalidData', ['as' => 'deduction.uoload_get_invalid']);


    /**
     * List & Options Form
     */
    $routes->get('area', 'Master\DeductionsController::getAllArea', ['as' => 'deduction.area']);
    $routes->get('area_grup', 'Master\DeductionsController::getAllAreaGrup', ['as' => 'deduction.area_grup']);
    $routes->get('payroll_rules', 'Master\DeductionsController::getAllPayrollRules', ['as' => 'deduction.payroll_rules']);
    $routes->get('options/company', 'Master\DeductionsController::getOptionsCompany', ['as' => 'deduction.company_options']);
    $routes->get('options/area', 'Master\DeductionsController::getOptionsArea', ['as' => 'deduction.area_options']);
    $routes->get('options/areagroup', 'Master\DeductionsController::getOptionsAreaGroup', ['as' => 'deduction.areagroup_options']);
    $routes->get('options/glaccount', 'Master\DeductionsController::getOptionsGLAccount', ['as' => 'deduction.glaccount_options']);
});
