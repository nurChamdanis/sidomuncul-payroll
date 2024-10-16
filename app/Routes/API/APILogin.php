<?php

/**
 * API Login Routes
 */

use App\API\V1\Authentication\AuthenticationController;

$routes->group('api', static function ($routes) {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
    $routes->post('login', [AuthenticationController::class, 'login']);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
    $routes->post('logout',[AuthenticationController::class, 'logout'], ['filter' => 'auth_api']);
});