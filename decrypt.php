<?php

function decrypt_data($data, $encryption_key) {
    $iv_length = 16;
    $data = base64_decode($data);
    $iv = substr($data, 0, $iv_length);
    $encrypted_data = substr($data, $iv_length);
    $iv = substr($iv, 0, $iv_length); // Truncate IV to 16 bytes if longer
    $decrypted_data = openssl_decrypt($encrypted_data, "AES-256-CTR", $encryption_key, OPENSSL_RAW_DATA, $iv);
    return $decrypted_data;
}
?>