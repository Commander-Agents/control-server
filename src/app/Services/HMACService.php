<?php

namespace App\Services;

class HMACService
{
    public function generateSignature($secretKey, $payload)
    {
        $payload = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $serverSignature = hash_hmac('sha256', $payload, $secretKey);
        return $serverSignature;
    }

    public function checkSignature($secretKey, $payload, $signature)
    {
        $serverSignature = $this->generateSignature($secretKey, $payload);
        return hash_equals($serverSignature, $signature);
    }
}
