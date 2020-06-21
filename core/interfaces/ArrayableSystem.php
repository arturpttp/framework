<?php


namespace Core\Interfaces;


interface ArrayableSystem
{

    public function get($key);
    public function getOrDefault($key, $defaults = null);
    public function getByGroup($group, $key);
    public function set($key, $value);
    public function has($key);
    public function remove($key);

}