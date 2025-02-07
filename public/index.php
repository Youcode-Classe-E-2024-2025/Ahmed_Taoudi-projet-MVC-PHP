<?php
require_once __DIR__."/../vendor/autoload.php";
use app\core\Session;
use app\core\Security;

Session::startSession();
// var_dump($_SESSION);

Security::generateCSRFToken();

// $db = Database::getInstance();
require_once __DIR__ . '/../app/config/routes.php';