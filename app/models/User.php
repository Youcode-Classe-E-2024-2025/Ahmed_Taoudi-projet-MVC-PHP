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

    public static function read($id)
    {
        $db = Database::getInstance();
        $sql = "SELECT * FROM users WHERE id = :id";
        $params = [':id' => $id];

        $db->query($sql, $params);
        $data = $db->fetch();
        $user=null;
        if($data){
            $user = new User($data['name'],$data['email'],'',Role::from($data['role']));
            $user->id = $data['id'];
        }
        return $user;
    } 

    /**
     * Read all users
     *
     * @return array
     */
    public static function readAll()
    {
        $db = Database::getInstance();
        $sql = "SELECT * FROM users Where role != :admin ";

        $db->query($sql,['admin'=> Role::ADMIN->value]);
        return $db->fetchAll();
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