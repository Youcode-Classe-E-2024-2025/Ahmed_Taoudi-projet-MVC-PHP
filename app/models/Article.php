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
     * Magic getter for accessing protected properties
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->$name ?? null;
    }

    /**
     * Magic setter for setting protected properties
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;
        }
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
        $row = $db->fetch();
        $article = new Article($row['id'],$row['title'],$row['content'],User::read($row['author_id']) ,$row['created_at']);
        return $article;
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
        $rows= $db->fetchAll();
        return self::toObjects($rows);
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
            ':author_id' => $this->author->id
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

    /**
     * return the last $n Articles
     * 
     * @param int $n
     * @return array
     */
    public static function last($n)
    {
         $db = Database::getInstance();
         $sql = "SELECT * FROM articles ORDER BY created_at , id  DESC LIMIT  ". (int)$n;
         $db->query($sql);
         $rows = $db->fetchAll();
         return self::toObjects($rows);
    }
    /**
     * create article objects 
     * 
     * @param array $rows
     * @return array
     */
    private static function toObjects($rows){
     $data=[];
     foreach ($rows as $row) {
          $author = User::read($row['author_id']) ?? new User();
          $articles[] = new Article($row['id'], $row['title'], $row['content'], $author, $row['created_at']);
      }
      return $articles;
    }
}