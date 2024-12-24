<?php

/**
 * Dashboard Routes
 */

$routes->get('/', function(){
    return redirect('dashboard');
});
$routes->get('/dashboard', 'Dashboard::index', ['filter' => 'auth','as' => 'dashboard']);
