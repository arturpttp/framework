<?php


namespace App\Controllers;


use App\Models\HomeModel;
use App\Repositories\UsersRepository;
use Core\Config\Config;
use Core\Database\DB;
use Core\Essetials\Controller;
use Core\Essetials\File;
use Core\Essetials\System;


class HomeController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->model = new HomeModel();
    }

    public function index() {
    }

    public function test()
    {
        echo "what";
    }

}