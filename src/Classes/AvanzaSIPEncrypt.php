<?php
namespace App\Classes;
use Exception;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Crypt\RSA;

class AvanzaSIPEncrypt
{
    public string $publicKey;
    public function __construct(string $publicKey){
        $this->setPublicKey($publicKey);
    }
    public function getPublicKey(): string{
        return $this->publicKey;
    }
    public function setPublicKey(string $publicKey): void{
        if(!file_exists( $publicKey))
            throw new Exception("No se pudo leer el archivo PEM",1000);
        $this->publicKey = $publicKey;
    }
    public function encrypt(string $data): string{
        // 1. Cargar clave pública desde el archivo .pem
        $pem = file_get_contents($this->publicKey);
        if (!$pem) {
            throw new Exception("No se pudo leer el archivo PEM", 1000);
        }
        $publicKey = PublicKeyLoader::load($pem)
            ->withPadding(RSA::ENCRYPTION_OAEP)
            ->withHash('sha256') // 3. OAEP con SHA-256
            ->withMGFHash('sha256');
        // 3. Encriptar
        $encrypted = $publicKey->encrypt($data);
        // 4. Codificar en base64
        return base64_encode($encrypted);
    }
}