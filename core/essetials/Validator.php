<?php


namespace Core\Essetials;


use Core\Database\DB;

class Validator
{

    /**
     * @var Model
     */
    private $model = null;
    private $errors = [];
    private $passed = true;

    public function __construct(){}

    public function make($data, $model, $method = "post")
    {
        $this->errors = [];
        $this->model = $model;
        foreach ($data as $name => $rules) {
            $field = ($method == "post") ? Request::post($name) : Request::get($name);
            $field = ucfirst($field);
            $display = in_array('display', $rules) ? $rules['display'] : $name;
            foreach ($rules as $rule => $value) {
                switch (strtolower($rule)) {
                    case 'required':
                        if (empty($field) || $field === '')
                            $this->errors[$name] = "{$display} is required!";
                        break;
                    case 'unique':
                        if ($this->model->contains($value, $field))
                            $this->errors[$name] = "{$display} already exists!";
                        break;
                    case 'min':
                        if (strlen($field) < $value)
                            $this->errors[$name] = "{$display} needs at least {$value} letters or numbers";
                        break;
                    case 'max':
                        if (strlen($field) > $value)
                            $this->errors[$name] = "{$display} can have a maximum of {$value} letters or numbers";
                        break;
                    case 'email':
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL))
                            $this->errors[$name] = "{$display} is not a valid email";
                        break;
                    case 'match':
                        $_field = ($method == "post") ? Request::post($value) : Request::get($value);
                        if (!($field === $_field))
                            $this->errors[$name] = "{$display} should match " . str_replace('_', ' ', $value);
                        break;
                }
            }
        }
        if (!empty($this->errors) && count($this->errors))
            $this->passed = false;
        return $this;
    }

    public function isPassed(): bool
    {
        return $this->passed;
    }

    public function errors(): array
    {
        return $this->errors;
    }

}