<?php

namespace app\Controllers;

use app\core\Controller;
use app\core\Session;
use app\core\View;
use app\enums\Role;
use app\models\Article;
use app\models\User;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Session::isLoggedIn() || $_SESSION['user']['role'] !== Role::ADMIN->value) {
            $this->redirect('/login');
        }

        $stats = [];

        $twig = View::getTwig();
        echo $twig->render('back/dashboard.twig', [
            'user' => $_SESSION['user'],
            'stats' => $stats,
        ]);
    }


    public function users()
    {
        if (!Session::isLoggedIn() || $_SESSION['user']['role'] !== Role::ADMIN->value) {
            $this->redirect('/login');
        }
        $users = User::readAll();

        $twig = View::getTwig();
        echo $twig->render('back/users.twig', [
            'user' => $_SESSION['user'],
            'users' => $users,
        ]);
    }
    public function articles()
    {
        if (!Session::isLoggedIn() || $_SESSION['user']['role']  !== Role::ADMIN->value) {
            $this->redirect('/login');
        }

        $articles = Article::readAll();

        $twig = View::getTwig();
        echo $twig->render('back/articles.twig', [
            'user' => $_SESSION['user'],
            'articles' => $articles,
        ]);
    }
}