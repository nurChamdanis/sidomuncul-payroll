<?php

/**
 * Login Routes
 */

use App\Controllers\Authentication\LoginController;

$routes->group('login', static function ($routes) {
    $routes->get('/', [LoginController::class, 'index']);
    $routes->get('failed', [LoginController::class, 'index']);
    $routes->get('attempt', [LoginController::class, 'index']);
    $routes->get('expired', [LoginController::class, 'index']);
    $routes->get('invalid_token', [LoginController::class, 'index']);
    $routes->get('reset_success', [LoginController::class, 'index']);
    $routes->get('misscode', [LoginController::class, 'index']);
    $routes->get('out', [LoginController::class, 'out']);
    
    $routes->post('check', [LoginController::class, 'check']);
});

$routes->get('forgot_password', [LoginController::class, 'forgot_password']);
$routes->get('reset_password', [LoginController::class, 'reset_password']); 
