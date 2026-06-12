<?php

class SecurityHelper {

    private static string $apiKey = "IDjiaosudh128eudaj8ih";

    public static function isAPIKeyValid(): bool {
        
        
    }

    public static function generateAPIAccessError() {
        (new Response(httpCode: 401))->generateResponse();
    }
}

?>