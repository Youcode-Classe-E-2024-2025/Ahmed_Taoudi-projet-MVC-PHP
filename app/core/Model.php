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
    
    abstract public function read() ;
    
    abstract public function update() ;
    
    abstract public function delete() ;
}