<?php
use app\core\Router;

$router = new Router();

$router->add('GET', '/', 'Home@index');
$router->add('GET', '/login', 'Home@showLoginForm');
$router->add('GET', '/register', 'Home@showRegisterForm');

$router->add('POST', '/login', 'User@login');
$router->add('POST', '/register', 'User@register');
$router->add('GET', '/logout', 'User@logout');

// Dashboard
$router->add('GET', '/admin/dashboard', 'Dashboard@index');
$router->add('GET', '/admin/users', 'Dashboard@users');
$router->add('GET', '/admin/articles', 'Dashboard@articles');
$router->add('GET', '/admin/articles', 'Article@articles');

$router->dispatch();


