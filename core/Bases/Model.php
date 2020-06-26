<?php


namespace Core\Bases;


use Core\Database\Database;

class Model extends Database
{

    public $table;

    public function __construct()
    {
        if (!$this->table)
            error(__CLASS__ . " table not specified");
        try {
            parent::__construct($this->table);
        } catch (\Exception $e) {
            error($e->getMessage());
        }
    }

}