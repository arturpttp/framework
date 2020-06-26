<?php


namespace App\Models;


use Core\Bases\Model;
use Core\User\User;

class UserModel extends Model
{

    public $table = "users";

    public function __construct()
    {
        parent::__construct();
    }

    public function getProfile($id)
    {
        return User::get($id);
    }

}