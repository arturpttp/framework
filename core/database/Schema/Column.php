<?php


namespace Core\Database\Schema;


use Core\Entities\Fluent;
use Illuminate\Database\Schema\Builder;

class Column extends Fluent
{

    private $name = false;
    private $primary = false;
    private $type = null;
    private $length = 200;
    private $nullable = false;
    private $defaultValue = null;
    private $types = [
        'string',
        'int',
        'text',
        'date',
        'boolean'
    ];

    public function string($name, $length = 200)
    {
        $this->name = $name;
        $this->length = $length;
        $this->type = "string";
        return $this;
    }

    public function primary($name, $primary = true)
    {
        $this->primary = $primary;
        return $this->int($name);
    }

    public function int($name, $length = 11)
    {
        $this->name = $name;
        $this->length = $length;
        $this->type = "int";
        return $this;
    }

    public function nullable($nullable = true)
    {
        $this->nullable = $nullable;
        return $this;
    }

    public function defaultValue($value)
    {
        $this->defaultValue = $value;
        return $this;
    }

}