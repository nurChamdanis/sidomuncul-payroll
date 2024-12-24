<?php

use Config\Encryption;

if (!function_exists('encrypt_data')) {
    function encrypt_data($data, $key)
    {
        $db = \Config\Database::connect();
        $data = $db->query("SELECT encrypt_data('{$data}','$key') as encrypted_key")->getRow();
        return !empty($data) ? $data->encrypted_key : '';
    }
}

if (!function_exists('decrypt_data')) {
    function decrypt_data($encryptedData, $key)
    {
        $db = \Config\Database::connect();
        $data = $db->query("SELECT decrypt_data('{$encryptedData}','$key') as decrypted_data")->getRow();
        return !empty($data) ? $data->decrypted_data : '';
    }
}
