<?php


namespace App\Controllers;


use App\Models\HomeModel;
use CodeIgniter\Services;
use Core\Bases\Controller;

class HomeController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->model = new HomeModel();
    }

    public function index()
    {
    }

    public function test() {}

}