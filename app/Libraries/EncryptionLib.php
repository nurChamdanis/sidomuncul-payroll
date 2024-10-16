<?php

namespace App\Libraries;

class EncryptionLib
{
    private $cipher = "aes-128-ecb"; // Matching MySQL AES_ENCRYPT default behavior
    private $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function encryptData($data)
    {
        // Encrypt the data
        $encryptedData = openssl_encrypt($data, $this->cipher, $this->key, OPENSSL_RAW_DATA);
        
        // Base64 encode the result
        return base64_encode($encryptedData);
    }

    public function decryptData($encryptedData)
    {
        // Base64 decode the data
        $decodedData = base64_decode($encryptedData);
        
        // Decrypt the data
        return openssl_decrypt($decodedData, $this->cipher, $this->key, OPENSSL_RAW_DATA);
    }
}
