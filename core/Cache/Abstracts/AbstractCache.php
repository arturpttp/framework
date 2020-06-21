<?php


namespace Core\Cache\Abstracts;


use Core\Cache\CacheInterface;

abstract class AbstractCache implements CacheInterface
{

    public $items = [];

    public function get($key, $defaults = null)
    {
        if (!$this->has($key))
            $this->set($key, $defaults);
        return $this->items[$key];
    }

    public function set($key, $value, $time = 0)
    {
        $this->items[$key] = $value;
    }

    public function delete($key)
    {
        if ($this->has($key))
            unset($this->items[$key]);
    }

    public function clear($keep = [])
    {
        foreach ($this->items as $item) {
            if (!in_array($item, $keep))
                $this->delete($item);
        }
    }

    public function getMultiple($keys, $default = null)
    {
        $results = [];
        foreach ($keys as $key)
            $results[$key] = $this->get($key, $default);
        return $results;
    }

    public function setMultiple($values, $times = 0)
    {
        foreach ($values as $key => $value)
            $this->set($key, $value, $times);
    }

    public function deleteMultiple($keys)
    {
        foreach ($keys as $key)
            $this->delete($key);
    }

    public function has($key)
    {
        return isset($this->items[$key]) && $this->items[$key] != null;
    }

    public function all()
    {
        return $this->items;
    }

}