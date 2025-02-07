<?php

namespace app\Controllers;

use app\core\Controller;
use app\core\Security;
use app\core\Session;
use app\core\View;
use app\models\Article;

class HomeController extends Controller
{
    public function index()
    {
        // Sample data for articles
        $articles = Article::last(3);
        // echo "<pre>";
        // var_dump($articles);
        // echo "</pre>";

        $twig = View::getTwig();
        $data = [
            'articles' => $articles,
            'user'=>Session::getUser(),
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
