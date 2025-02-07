<?php

namespace app\core;

use PDO;
use PDOException;
use Dotenv\Dotenv;

class Database
{
    private static $instance = null;
    private $host;
    private $port;
    private $dbname;
    private $user;
    private $password;
    private $pdo;
    private $stmt;

    /**
     * Private constructor to prevent instantiation
     */
    private function __construct()
    {
                // Load environment variables
        $dotenv = Dotenv::createImmutable(__DIR__ . "/../../");
        $dotenv->load();
        
        $this->host = $_ENV['DB_HOST'];
        $this->port = $_ENV['DB_PORT'];
        $this->dbname =$_ENV['DB_NAME'] ;
        $this->user =$_ENV['DB_USER'];
        $this->password =$_ENV['DB_PASSWORD'] ;

        $this->connect();
    }


    /**
     * Get the single instance of the Database class
     *
     * @return Database
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Establishes a PDO connection to the database
     */
    private function connect()
    {
        $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";
        // echo $dsn ; die;

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Prepares a SQL query
     * and  Executes it with bound parameters
     * @param string $sql
     *  @param array $params
     * @return bool
     */
    public function query($sql,$params = [])
    {
        $this->stmt = $this->pdo->prepare($sql);
       
        return  $this->stmt->execute($params);
    }


    /**
     * Fetch all results from the last executed query
     * 
     * @return array
     */
    public function fetchAll()
    {
        return $this->stmt->fetchAll();
    }

    /**
     * Fetch a single result from the last executed query
     * 
     * @return array
     */
    public function fetch()
    {
        return $this->stmt->fetch();
    }

    /**
     * Fetch the last inserted ID
     * 
     * @return int
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * Begins a transaction
     */
    public function beginTransaction()
    {
        $this->pdo->beginTransaction();
    }

    /**
     * Commits a transaction
     */
    public function commit()
    {
        $this->pdo->commit();
    }

    /**
     * Rolls back a transaction
     */
    public function rollBack()
    {
        $this->pdo->rollBack();
    }
}
