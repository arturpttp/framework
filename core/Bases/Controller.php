<?php


namespace Core\Bases;


use Core\Html;
use Core\System\Session;
use Core\System\System;
use stdClass;

class Controller
{

    use System;

    public $view;
    public $model;
    public $app = null;
    public $errors, $inputs, $success;
    private $viewPath, $layoutPath;

    public function __construct()
    {
        $this->app = self::app();
        $this->view = new stdClass;
        $this->view->header = true;
        $this->view->footer = true;
        if (Session::has('errors')) {
            $this->errors = Session::get('errors');
            Session::remove('errors');
        }
        if (Session::has('inputs')) {
            $this->inputs = Session::get('inputs');
            Session::remove('inputs');
        }
        if (Session::has('success')) {
            $this->success = Session::get('success');
            Session::remove('success');
        }
    }

    public function addError($error, $autoPrint = false) {
        $this->errors[] = $error;
        if ($autoPrint)
            echo Html::error($error, "Error");
        return $this;
    }

    public function showErrors()
    {
        if ($this->errors) {
            echo "<div class='alert alert-danger alert-dismissible' role='alert'>
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>
        </button>";
            foreach ($this->errors as $msg)
                echo "<p><i class='glyphicon glyphicon-alert'></i> {$msg}></p>";
            echo "</div>";
        }
    }

    public function showSuccess()
    {
        if ($this->success) {
            echo "<div class='alert alert-success alert-dismissible' role='alert'>
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>
        </button>";
            foreach ($this->success as $msg)
                echo "<p><i class='glyphicon glyphicon-alert'></i> {$msg}></p>";
            echo "</div>";
        }
    }

    public function renderView($path, $layout = "layout", $extension = GENERAL_EXTENSION)
    {
        if (!isset($this->view->title)) {
            $et = explode("/", $path);
            $this->view->title = $et[count($et) - 1];
        }
        $this->viewPath = $path;
        $this->layoutPath = $layout;
        if (is_null($layout)) {
            $this->viewContent($extension);
        } else {
            $this->layout($extension);
        }
    }

    private function viewContent($extension)
    {
        if (file_exists(__DIR__ . "/../../app/Views/{$this->viewPath}." . $extension))
            require_once __DIR__ . "/../../app/Views/{$this->viewPath}." . $extension;
        else
            error("View path not found");
    }

    private function layout($extension)
    {
        $file = __DIR__ . "/../../app/Views/{$this->layoutPath}.{$extension}";
        if (file_exists($file)) {
            require_once $file;
        } else {
            error("Layout path not found");
        }
    }

    public function setTitle($title)
    {
        $this->view->title = $title;
        return $title;
    }

    public function unLogged()
    {
        System::redirect("/", [
            "errors" => [
                "You need to login to access this page"
            ]
        ]);
    }

    public function noPermission()
    {
        System::redirect("/", [
            "errors" => [
                "You don't have permission to do that"
            ]
        ]);
    }

}