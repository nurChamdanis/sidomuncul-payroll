<?php

/**
 * Dashboard Routes
 */

$routes->get('/lang/set', 'Authentication\LangController::set');

$routes->group('payroll_logs', ['filter' => 'auth'], function ($routes) {
    $routes->post('datatable', 'Shared\PayrollLogsController::getDataTable', ['as' => 'payroll_logs.datatable']);
});