<?php

namespace app\Controllers;

use app\core\Auth;
use app\core\Controller;
use app\core\View;


class UserController extends Controller
{
    public function login(){
        $auth = new Auth();
        $auth->login();
    }

    public function register(){
        $auth = new Auth();
        $auth->register();
    }

    public function logout(){
        $auth = new Auth();
        $auth->logout();
    }
}