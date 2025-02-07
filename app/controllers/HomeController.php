<?php

namespace app\Controllers;

use app\core\Controller;
use app\core\Security;
use app\core\Session;
use app\core\View;

class HomeController extends Controller
{
    public function index()
    {
        // Sample data for articles
        $articles = [
            [
                'title' => 'How to Use Tailwind CSS',
                'author' => 'John Doe',
                'content' => 'Tailwind CSS is a utility-first CSS framework...',
                'date' => '2023-10-01'
            ],
            [
                'title' => 'Introduction to Twig',
                'author' => 'Jane Smith',
                'content' => 'Twig is a powerful templating engine for PHP...',
                'date' => '2023-10-05'
            ]
        ];

        $twig = View::getTwig();
        $data = [
            'articles' => $articles,
            'user'=>Session::getData('user'),
            'flash' => [
                'error' => Session::getFlashMessage('error'),
                'message' => Session::getFlashMessage('message')
            ],
        ];

        echo $twig->render('home.html.twig', $data);
    }

    public function showLoginForm()
    {
        return $this->showForm('login');
    }

    public function showRegisterForm()
    {
        return $this->showForm('register');
    }
    private function showForm($name)
    {
        $data = [
            'csrf_token' => Security::generateCSRFToken(),
            'old' => Session::getData('old'),
            'errors' => Session::getData('errors'),
            'flash' => [
                'error' => Session::getFlashMessage('error'),
                'message' => Session::getFlashMessage('message')
            ],
        ];
        // Render the Twig template
        $twig = View::getTwig();
        echo $twig->render("auth/{$name}.twig", $data);
    }
}
