<?php
namespace app\Controllers;
use app\core\Controller;
use app\core\Session;
use app\models\Article;
use app\core\View;

class ArticleController extends Controller
{
    private $articleModel;
    

    public function __construct()
    {
        $this->twig = View::getTwig();
        // Initialize the Article model
        $this->articleModel = new Article();
    }

    /**
     * Display all articles
     */
    public function index()
    {
        // Fetch all articles from the database
        $articles = Article::readAll();
        $data = [];
        echo  $this->twig->render('home.html.twig', $data);
    }


    /**
     * Handle the creation of a new article
     */
    public function store()
    {
        // Get data from POST request
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $author_id = $_POST['author_id'] ?? null;

        // Validate input (you can add more robust validation here)
        if (empty($title) || empty($content)) {
            // Redirect back with an error message
            $this->redirect('articles/create', ['error' => 'Title and content are required.']);
            return;
        }

        // Create a new article instance
        $article = new Article(null, $title, $content, $author_id);

        // Save the article to the database
        if ($article->create()) {
            // Redirect to the articles list page
            $this->redirect('articles/index', ['success' => 'Article created successfully.']);
        } else {
            // Redirect back with an error message
            $this->redirect('articles/create', ['error' => 'Failed to create article.']);
        }
    }

    /**
     * Show details of a specific article
     *
     * @param int $id
     */
    public function show($id)
    {
        // Fetch the article by ID
        $article = Article::read($id);

        if (!$article) {
            $data = ['message' => 'Article not found.'];
            echo  $this->twig->render('errors/404.twig', $data);
            return;
        }

        $data = ['article' => $article];
        echo  $this->twig->render('front/article.twig', $data);
        return;
    }

    /**
     * Show form to edit an existing article
     *
     * @param int $id
     */
    public function edit($id)
    {
        // Fetch the article by ID
        $article = Article::read($id);

        if (!$article) {
            // If article not found, redirect to 404 or show an error
            $data =  ['message' => 'Article not found.'];
            echo  $this->twig->render('errors/404.twig', $data);
            return;
        }

        // Pass the article to the edit form
        $data =  ['article' => $article];
        echo  $this->twig->render('front/edit.twig', $data);
        return;
    }

    /**
     * Handle the update of an existing article
     *
     * @param int $id
     */
    public function update($id)
    {
        // Get data from POST request
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $author_id = $_POST['author_id'] ?? null;

        // Validate input (you can add more robust validation here)
        if (empty($title) || empty($content)) {
            // Redirect back with an error message
            $this->redirect("articles/edit/$id", ['error' => 'Title and content are required.']);
            return;
        }

        // Update the article in the database
        $article = new Article($id, $title, $content, $author_id);

        if ($article->update()) {
            // Redirect to the article details page
            $this->redirect("articles/show/$id", ['success' => 'Article updated successfully.']);
        } else {
            // Redirect back with an error message
            $this->redirect("articles/edit/$id", ['error' => 'Failed to update article.']);
        }
    }

    /**
     * Delete an article
     *
     * @param int $id
     */
    public function delete($id)
    {
        // Delete the article from the database
        if (Article::delete($id)) {
            // Redirect to the articles list page
            $this->redirect('articles/index', ['success' => 'Article deleted successfully.']);
        } else {
            // Redirect back with an error message
            $this->redirect('articles/index', ['error' => 'Failed to delete article.']);
        }
    }
}