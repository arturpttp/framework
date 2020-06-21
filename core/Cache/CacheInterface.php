<?php


namespace Core\Cache;


interface CacheInterface
{
    public function get($key, $defaults = null);
    public function set($key, $value, $time = 0);
    public function delete($key);
    public function clear($keep = []);
    public function getMultiple($keys, $default = null);
    public function setMultiple($values, $times = 0);
    public function deleteMultiple($keys);
    public function has($key);
    public function all();
}