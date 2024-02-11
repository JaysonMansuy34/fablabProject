<?php

namespace App\Service;

use DateTimeImmutable;

class JWTService
{
    /**
     * generate du jwt
     *
     * @param  array $header
     * @param  array $payload
     * @param  string $secret
     * @param  int $validity
     * @return string
     */
    public function generate(array $header, array $payload, string $secret, int $validity = 10800): string
    {
        if ($validity > 0) {
            $now = new DateTimeImmutable();
            $exp = $now->getTimestamp() + $validity;

            $payload['iat'] = $now->getTimestamp();
            $payload['exp'] = $exp;
        }


        //On encode tout en base64
        $base64Header = base64_encode(json_encode($header));
        $base64Payload = base64_encode(json_encode($payload));

        //Une fois "nettoie" les valeurs encodees (retrait des + / #)
        $base64Header = str_replace(['+', '/', "="], ['-', '_', ''], $base64Header);
        $base64Payload = str_replace(['+', '/', "="], ['-', '_', ''], $base64Payload);

        //Génère la signature 
        $secret = base64_encode($secret);

        $signature = hash_hmac('sha256', $base64Header . '.' . $base64Payload, $secret, true);

        $base64Signature = base64_encode($signature);

        $base64Signature = str_replace(['+', '/', "="], ['-', '_', ''], $base64Signature);

        //On crée le token 

        $jwt = $base64Header . '.' . $base64Payload . '.' . $base64Signature;

        return $jwt;
    }

    // Verifier le token est valide (correctement formé)
    public function isValid(string $token): bool
    {
        return preg_match(
            '/^[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+$/',
            $token
        ) === 1;
    }

    //On  recuper payload 
    public function getPlayLoad(string $token): array
    {
        // on separe le token 
        $array = explode('.', $token);
        //on decode le payload
        $payload = json_decode(base64_decode($array[1]), true);

        return $payload;
    }

    //On  recuper header 
    public function getHeader(string $token): array
    {
        // on separe le token 
        $array = explode('.', $token);
        //on decode le header
        $header = json_decode(base64_decode($array[0]), true);

        return $header;
    }

    //Verifi si le token a expiré 
    public function isExpired(string $token): bool
    {
        $payload = $this->getPlayLoad($token);

        $now = new DateTimeImmutable();

        return $payload['exp'] < $now->getTimestamp();
    }

    //On verifie  la signature du token

    public function check(string $token, string $secret)
    {
        //On recuper le header et le payload
        $header = $this->getHeader($token);
        $payload = $this->getPlayLoad($token);


        //regénère un token
        $verifToken = $this->generate($header, $payload, $secret, 0);

        return $token === $verifToken;
    }
}