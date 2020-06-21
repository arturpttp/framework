<?php


namespace Core\Cache;


interface Factory
{

    public function store($name = null): Repository;

}