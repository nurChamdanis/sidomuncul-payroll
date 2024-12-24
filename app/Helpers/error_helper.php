<?php

if (! function_exists('show_401')) {
    function show_401()
    {
        $response = service('response');

        // Set the status code to 401
        $response->setStatusCode(401);

        // Load view for 401 error
        echo view('errors/html/error_num', array('code' => 401,'message' => 'Unauthorized'));
        exit;
    }
}

if (! function_exists('show_error_custom')) {
    function show_error_custom($heading, $message, $code)
    {
        $response = service('response');

        // Set the provided status code
        $response->setStatusCode($code);

        // Load view for custom error
        echo view('errors/html/error_custom', ['heading' => $heading, 'message' => $message]);
        exit;
    }
}
