<?php

/**
 * Master Product
 */

$routes->group('pkh_product', ['filter' => 'auth'], function ($routes) {
    //
    $routes->get('/', 'PKHMaster\PKHProductController::index', ['as' => 'pkh_product']);
    // 
    $routes->get('create', 'PKHMaster\PKHProductController::create', ['as' => 'pkh_product.create']);

    $routes->post('datatable', 'PKHMaster\PKHProductController::getDataTable', ['as' => 'pkh_product.datatable']);
    $routes->post('store', 'PKHMaster\PKHProductController::actionCreate', ['as' => 'pkh_product.store']);
    /*
    $routes->post('update', 'Master\AllowancesController::actionUpdate', ['as' => 'allowance.update']);
    $routes->post('remove', 'Master\AllowancesController::actionRemove', ['as' => 'allowance.remove']);
    $routes->post('removeSelected', 'Master\AllowancesController::actionRemoveSelected', ['as' => 'allowance.remove_selected']);
    */

    $routes->get('options/company', 'PKHMaster\PKHProductController::getOptionsCompany', ['as' => 'pkh_product.category_options']); 
    $routes->get('options/area', 'PKHMaster\PKHProductController::getOptionsArea', ['as' => 'pkh_product.area_options']);
    $routes->get('options/role', 'PKHMaster\PKHProductController::getOptionsRole', ['as' => 'pkh_product.role_options']);
    $routes->get('options/pieces', 'PKHMaster\PKHProductController::getOptionsPieces', ['as' => 'pkh_product.pieces_options']);
    $routes->get('options/glaccount', 'PKHMaster\PKHProductController::getOptionsGLAccount', ['as' => 'pkh_product.glaccount_options']);
     
}); 