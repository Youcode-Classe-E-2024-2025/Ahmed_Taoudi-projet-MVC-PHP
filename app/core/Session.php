<?php 

namespace app\core;

class Session
{

    public static function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function requireAuth() {
        if (!self::isLoggedIn()) {
            $_SESSION['error'] = 'Please login to continue';
            header('location: /login'); 
            exit;
        }
    }

    public static function isLoggedIn() {
        return isset($_SESSION['user']) && !empty($_SESSION['user']);
    }

    public static function setFlashMessage($type, $message) {
        $_SESSION[$type] = $message;
    }

    public static function hasFlashMessage($type) {
        return isset($_SESSION[$type]);
    }
   
    public static function getFlashMessage($type) {
        if (self::hasFlashMessage($type)) {
            $message = $_SESSION[$type];
            unset($_SESSION[$type]);
            return $message;
        }
        return null;
    }


    public static function setData($name,$content){
        $_SESSION[$name] = $content;
    }
    
    public static function getData($name){
        if (isset($_SESSION[$name])) {
            $content = $_SESSION[$name];
            unset($_SESSION[$name]);
            return $content;
        }
        return null;
    }

    public static function getUser(){
        if (isset($_SESSION['user'])) {
            $content = $_SESSION['user'];
            return $content;
        }
        return null;
    }

}