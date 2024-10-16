<?php

/**
 * Master System Routes
 */

$routes->group('master_system', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Settings\MasterSystemController::index', ['as' => 'system']);
    $routes->get('options', 'Settings\MasterSystemController::getOptions', ['as' => 'system.options']);
    $routes->get('create', 'Settings\MasterSystemController::create', ['as' => 'system.create']);
    $routes->get('id/(:segment)/(:segment)', 'Settings\MasterSystemController::id/$1/$2', ['as' => 'system.id']);
    $routes->get('edit/(:segment)/(:segment)', 'Settings\MasterSystemController::edit/$1/$2', ['as' => 'system.edit']);
    $routes->post('datatable', 'Settings\MasterSystemController::getDataTable', ['as' => 'system.datatable']);
    $routes->post('store', 'Settings\MasterSystemController::actionCreate', ['as' => 'system.store']);
    $routes->post('update', 'Settings\MasterSystemController::actionUpdate', ['as' => 'system.update']);
    $routes->post('remove', 'Settings\MasterSystemController::actionRemove', ['as' => 'system.remove']);
});
