<?php 

namespace app\core;


abstract class Model
{

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null; 
    }

    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }

    abstract public function create() ;
    
    abstract public static  function read($id) ;

    abstract public static  function readAll() ;
    
    abstract public function update() ;
    
    abstract public static function delete($id) ;
}