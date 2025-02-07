<?php

namespace app\core;

class Security
{

    public static function XSS($data)
    {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    public static function generateCSRFToken()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function verifyCSRFToken($token)
    {
        if (isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $token) {
            return true;
        }
        return false;
    }

    public static function regenerateCSRFToken()
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}
