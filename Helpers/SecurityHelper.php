<?php

class SecurityHelper {

    private static string $apiKey = "IDjiaosudh128eudaj8ih";

    public static function isAPIKeyValid(): bool {
        
        return isset($_GET["api_key"]) && $_GET["api_key"] === self::$apiKey;
        
        
    }

    public static function generateAPIAccessError() {
        (new Response(httpCode: 401))->generateResponse();
    }
}

?>