<?php


namespace Core\Cache;


use Closure;

class RepositoryClass extends Cache implements Repository
{


    public function pull($key, $default = null){}

    public function put($key, $value){}

    public function add($key, $value){}

    public function increment($key, $value = 1){}

    public function decrement($key, $value = 1){}

    public function forever($key, $value){}

    public function remember($key, Closure $callback){}

    public function sear($key, Closure $callback){}

    public function rememberForever($key, Closure $callback){}

    public function forget($key){}

    public function getStore(): Store {
        return null;
    }
}