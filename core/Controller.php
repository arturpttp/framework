<?php


namespace Core;


class Controller
{

    public $view;
    private $viewPath, $layoutPath;

    public function __construct()
    {
        $this->view = new \stdClass;
        $this->view->header = true;
        $this->view->footer = true;
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
        }else {
            $this->layout($extension);
        }
    }

    private function layout($extension)
    {
        $file = __DIR__ . "/../app/Views/{$this->layoutPath}.{$extension}";
        if (file_exists($file)) {
            require_once $file;
        }else {
            error("Layout path not found");
        }
    }

    private function viewContent($extension)
    {
        if (file_exists(__DIR__ . "/../app/Views/{$this->viewPath}.".$extension))
            require_once __DIR__ . "/../app/Views/{$this->viewPath}.".$extension;
        else
            error("View path not found");
    }

    public function setTitle($title) {
        $this->view->title = $title;
        return $title;
    }

}