<?php
/**
 * Code by: Nick Rolando
 */
namespace PetStoreInc\model;

class ModelAbstract
{
    private $data;
    
    public function __construct() {
        $this->data = array();
    }
    
    public function __set($name, $value) {
        $this->data[$name] = $value;
    }
    
    public function __get($name) {
        if(array_key_exists($name, $this->data)) {
            return $this->data[$name];
        } else {
            throw new \Exception("Requesting invalid property '" . $name . "'.");
        }
    }
    
    /**
     * Allows for get[key]/set[key]
     * @throws \Exception
     */
    public function __call($method, $args) {
        switch(substr($method, 0, 3)) {
            case 'get':
                $key = substr($method, 3);
                return $this->getData($key);
            case 'set':
                if(!isset($args[0])) {
                    throw new \Exception("No value passed to setter method in " . get_class($this) . "::" . $method . "(" . print_r($args, 1) . ")");
                }
                $key = substr($method, 3);
                return $this->setData($key, $args[0]);
        }
        throw new \Exception("Invalid method " . get_class($this) . "::" . $method . "(" . print_r($args, 1) . ")");
    }
    
    /**
     * Set model attribute(s). If no values passed, set $this->data to empty array.
     * @param key Must be string or array. If array, the array will overwrite $this->data entirely.
     * @return $this
     * @throws \Exception
     */
    public function setData($key = null, $v = null) {
        if(is_null($key)) {
            $this->data = array();
        }
        if(is_array($key)) {
            $this->data = $key;
        } elseif(is_string($key) || is_int($key)) {
            $this->data[$key] = $v;
        } else {
            throw new \Exception("Invalid parameter type passed to " . get_class($this) . "::" . $method);
        }
        
        return $this;
    }
    
    public function getData(string $key = null) {
        if(is_null($key)) {
            return $this->data;
        }
        
        if(array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }
        
        return null;
    }
}

