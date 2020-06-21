<?php


namespace Core;


use Core\Interfaces\ArrayableSystem;
use PDO;

class Config implements ArrayableSystem
{

    public $items;

    public function __construct($items)
    {
        $this->items = $items;
    }

    public static function getConfig($items)
    {

        return new Config($items);
    }

    public function get($key)
    {
        return $this->items[$key];
    }

    public function getOrDefault($key, $defaults = null)
    {
        if (!$this->has($key) && $defaults != null)
            $this->set($key, $defaults);
        return $this->get($key);
    }

    public function getByGroup($group, $key)
    {
        return $this->get($group)[$key];
    }

    public function set($key, $value)
    {
        $this->items[$key] = $value;
    }

    public function has($key)
    {
        return in_array($key, $this->items);
    }

    public function remove($key)
    {
        $this->set($key, null);
    }
    
}