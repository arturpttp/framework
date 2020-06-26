<?php


namespace App\Models;

use Core\Bases\Model;

class HomeModel extends Model
{

    public $table = "users";

    public function __construct()
    {
        parent::__construct();
    }

}