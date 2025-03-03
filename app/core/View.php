<?php 

namespace app\core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class View
{
    private static $twig;

    public static function getTwig()
    {
        if (!self::$twig) {
            
            $loader = new FilesystemLoader(__DIR__ . '/../views'); 

            self::$twig = new Environment($loader , [
                'debug' => true,
                'cache' => false, 
            ]);
        }

        return self::$twig;
    }
}

