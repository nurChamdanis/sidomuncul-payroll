<?php

/**
 * API Login Routes
 */

use App\API\V1\PKH\PKHController;

$routes->group('api', static function ($routes) {     
    $routes->group('pkh', ['filter' => 'auth_api'], static function ($routes) {    
        $routes->group('recruitment', static function ($routes) {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  
            $routes->post('register', [PKHController::class, 'registerFromRecruitment']);
        });
    });
});