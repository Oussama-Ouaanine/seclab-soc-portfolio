<?php
class Encryptor {
    private $key;

    public function __construct($key = 'your-very-strong-secret-key') {
        $this->key = hash('sha256', $key);
    }

    public function encrypt($plaintext) {
        $iv = openssl_random_pseudo_bytes(16);
        $ciphertext = openssl_encrypt($plaintext, 'aes-256-cbc', $this->key, 0, $iv);
        return base64_encode($iv . $ciphertext);
    }

    public function decrypt($encrypted) {
        $data = base64_decode($encrypted);
        $iv = substr($data, 0, 16);
        $ciphertext = substr($data, 16);
        return openssl_decrypt($ciphertext, 'aes-256-cbc', $this->key, 0, $iv);
    }
}

