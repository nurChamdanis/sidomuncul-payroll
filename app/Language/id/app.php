<?php

// Get the directory path
$routesDir = APPPATH . 'Language/id/';

// Set Lang Array
$lang = array();

// Include files and populate $lang array
includeAllLanguage($routesDir, $lang);

return $lang;
