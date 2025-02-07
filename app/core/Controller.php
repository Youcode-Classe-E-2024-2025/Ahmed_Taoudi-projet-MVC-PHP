<?php 

namespace app\core;

class Controller
{
    public $twig;

    protected function redirect($path) {
        header("Location: {$path}");
        exit;
    }

}
