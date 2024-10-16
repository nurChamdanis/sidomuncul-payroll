<?php

/* 
Master kompensasi routes
*/

$routes->group('master_kompensasi', ['filter' => 'auth'], function ($routes){
    $routes->get('/', 'Master\MasterCompensationController::index', ['as' => 'compensation']);
    $routes->get('options_company', 'Master\MasterCompensationController::getOptionsCompany', ['as' => 'compensation.companyOptions']);
    $routes->get('options_area', 'Master\MasterCompensationController::getOptionsArea', ['as' => 'compensation.areaOptions']);
    $routes->get('options_role', 'Master\MasterCompensationController::getOptionsRole', ['as' => 'compensation.unitOptions']);
    $routes->get('options_employee', 'Master\MasterCompensationController::getOptionsEmployee', ['as' => 'compensation.employeeOptions']);
    $routes->get('options_compensationType', 'Master\MasterCompensationController::getOptionsCompensationType', ['as' => 'compensation.compensationTypeOptions']);
    $routes->get('create', 'Master\MasterCompensationController::create', ['as' => 'compensation.create']);
    $routes->get('id/(:segment)', 'Master\MasterCompensationController::id/$1', ['as' => 'compensation.id']);
    $routes->get('edit/(:segment)', 'Master\MasterCompensationController::edit/$1', ['as' => 'compensation.edit']);
    $routes->get('upload', 'Master\MasterCompensationController::upload', ['as' => 'compensation.upload']);
    // $routes->get('remove/(:segment)', 'Master\MasterCompensationController::actionRemove/$1', ['as' => 'compensation.remove']);
    $routes->post('removeSelected', 'Master\MasterCompensationController::actionRemoveSelected', ['as' => 'compensation.remove_selected']);
    $routes->post('datatable', 'Master\MasterCompensationController::getDataTable', ['as' => 'compensation.datatable']);
    $routes->post('store', 'Master\MasterCompensationController::actionCreate', ['as' => 'compensation.store']);
    $routes->post('datatable_detail', 'Master\MasterCompensationController::getTableDetail', ['as' => 'compensation.tableDetail']);
    $routes->post('update', 'Master\MasterCompensationController::actionUpdate', ['as' => 'compensation.update']);
    $routes->post('download', 'Master\MasterCompensationController::actionDownload', ['as' => 'compensation.download']);
    // for upload purposes
    $routes->post('upload_template/read', 'Master\MasterCompensationController::uploadTemporary', ['as' => 'compensation.uploadTemp']);
    $routes->post('upload_template/show', 'Master\MasterCompensationController::showTemporary', ['as' => 'compensation.showTemp']);
    $routes->post('upload_template/get-invalid', 'Master\MasterCompensationController::getInvalidData', ['as' => 'compensation.invalidData']);
    $routes->post('upload_template/submit', 'Master\MasterCompensationController::actionSubmitExcel', ['as' => 'compensation.submitExcel']);
    $routes->post('download_template/excel', 'Master\MasterCompensationController::actionDownloadTemplate', ['as' => 'allowance.downloadTemplate']);
});