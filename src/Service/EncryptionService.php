<?php

namespace App\Service;

class EncryptionService
{
    private string $key;
    private string $cipher = 'aes-256-cbc';

    public function __construct(string $appEncryptionKey)
    {
        $this->key = hash('sha256', $appEncryptionKey, true); // Clé de 32 octets pour AES-256
    }

    public function encrypt(string $plaintext): string
    {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->cipher));
        $ciphertext = openssl_encrypt($plaintext, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv);

        return base64_encode($iv . $ciphertext); // IV + message chiffré
    }

    public function decrypt(string $encrypted): string
    {
        $data = base64_decode($encrypted);
        $ivLength = openssl_cipher_iv_length($this->cipher);
        $iv = substr($data, 0, $ivLength);
        $ciphertext = substr($data, $ivLength);

        return openssl_decrypt($ciphertext, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv);
    }
}
