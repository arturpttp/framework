<?php


namespace Core\database;


use Core\Cache\Factory;
use Core\Cache\Repository;

class DatabaseFactory implements Factory
{
    public function store($name = null): Repository {return null;}
}