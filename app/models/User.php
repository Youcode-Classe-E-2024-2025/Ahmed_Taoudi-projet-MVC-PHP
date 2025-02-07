<?php 

namespace app\models;

use app\core\Database;
use app\core\Model;
use app\enums\Role;
use PDOException;

class User extends Model
{
    protected $id;
    protected $name;
    protected $email;
    protected $password;
    protected Role $role;
    public function __construct($name=null,$email=null,$password=null,$role=Role::USER)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }


    public function create(): bool
    {
        $db = Database::getInstance();
        $query = "INSERT INTO users
                  (name, email, password, role)
                  VALUES 
                  (:name, :email, :password, :role)";

        $params = [
            ':name' => $this->name,
            ':email' => $this->email,
            ':password' => $this->password,
            ':role' => $this->role->value
        ];

        try {
            $db->query($query, $params);
            return true;
        } catch (PDOException $e) {
            error_log("Error creating user: " . $e->getMessage());
            return false;
        }
    }

    public static function read($id){

    } 

    public static function readAll(){

    } 
    
    public function update(){

    } 
    
    public static function delete($id){

    } 
    public function login(string $email, string $password)
    {
        $db = Database::getInstance();
        $query = "SELECT u.* FROM users u 
                  WHERE u.email = :email ";

        $params = [
            ':email' => $email
        ];

        try {
            $db->query($query, $params);
            $user = $db->fetch();

            if ($user && password_verify($password, $user['password'])) {
                return $user;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error logging in: " . $e->getMessage());
            return false;
        }
    }

}