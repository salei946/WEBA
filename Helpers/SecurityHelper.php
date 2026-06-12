<?php

class SecurityHelper {

    private static string $apiKey = "IDjiaosudh128eudaj8ih";

    public static function isAPIKeyValid(): bool {

        $headers = getallheaders();

        if (!isset($headers['Authorization'])) {
            return false;
        }

        $authorization = $headers['Authorization'];

        if (!str_starts_with($authorization, "Bearer ")) {
            return false;
        }

        $token = substr($authorization, 7);

        return $token === self::$apiKey;
    }

    public static function generateAPIAccessError() {
        (new Response(httpCode: 401))->generateResponse();
    }
}

?>