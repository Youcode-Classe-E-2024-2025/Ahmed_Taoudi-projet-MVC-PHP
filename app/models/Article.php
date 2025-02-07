<?php

namespace app\models;

use app\core\Database;
use app\core\Model;
use app\models\User;

// class Article extends Model


class Article extends Model
{
    protected $id;
    protected $title;
    protected $content;
    Protected  User $author;
    protected $created_at;

    public function __construct($id = null, $title = '', $content = '', $author = new User(), $created_at = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->author = $author;
        $this->created_at = $created_at;
    }

    /**
     * Create a new article
     *
     * @return bool
     */
    public function create()
    {
        $db = Database::getInstance();
        $sql = "INSERT INTO articles (title, content, author_id) VALUES (:title, :content, :author_id)";
        $params = [
            ':title' => $this->title,
            ':content' => $this->content,
            ':author_id' => $this->author->id
        ];

        if ($db->query($sql, $params)) {
            $this->id = $db->lastInsertId();
            return true;
        }

        return false;
    }

    /**
     * Read an article by ID
     *
     * @param int $id
     * @return array|null
     */
    public static function read($id)
    {
        $db = Database::getInstance();
        $sql = "SELECT * FROM articles WHERE id = :id";
        $params = [':id' => $id];

        $db->query($sql, $params);
        return $db->fetch();
    }

    /**
     * Read all articles
     *
     * @return array
     */
    public static function readAll()
    {
        $db = Database::getInstance();
        $sql = "SELECT * FROM articles";

        $db->query($sql);
        return $db->fetchAll();
    }

    /**
     * Update an existing article
     *
     * @return bool
     */
    public function update()
    {
        $db = Database::getInstance();
        $sql = "UPDATE articles SET title = :title, content = :content, author_id = :author_id WHERE id = :id";
        $params = [
            ':id' => $this->id,
            ':title' => $this->title,
            ':content' => $this->content,
            ':author_id' => $this->author
        ];

        return $db->query($sql, $params);
    }

    /**
     * Delete an article by ID
     *
     * @param int $id
     * @return bool
     */
    public static function delete($id)
    {
        $db = Database::getInstance();
        $sql = "DELETE FROM articles WHERE id = :id";
        $params = [':id' => $id];

        return $db->query($sql, $params);
    }
}