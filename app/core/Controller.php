<?php 

namespace app\core;

class Controller
{

    protected function redirect($path) {
        header("Location: {$path}");
        exit;
    }

}
