<?php


namespace Core\system;


use Core\Database\Database;

class Model extends Database
{

    protected $table;

    public function __construct()
    {
        parent::__construct($this->table);
    }

}