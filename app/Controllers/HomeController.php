<?php


namespace App\Controllers;


use Core\Controller;

class HomeController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index() {
        $this->renderView("home/index");
    }

}