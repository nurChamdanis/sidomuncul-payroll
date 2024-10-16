<?php

/**
 * API Login Routes
 */

use App\API\V1\Batch\GeneratePayroll\GenerateController;

$routes->group('api', static function ($routes) {     
    $routes->group('batch', static function ($routes) {    
        $routes->group('payroll-generate', static function ($routes) {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  
            $routes->get('attendances', [GenerateController::class, 'generateAttendances']);
        });
    });
});