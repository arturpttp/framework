<?php


namespace App\Repositories;


use App\Repositories\Stores\UserStore;
use Core\Cache\Abstracts\AbstractRepository;
use Core\Cache\Abstracts\AbstractStore;
use Core\Cache\Abstracts\Database\DatabaseRepository;
use Core\Cache\Abstracts\Database\DatabaseStore;

class UsersRepository extends DatabaseRepository
{

    public function __construct()
    {
        parent::__construct(new UserStore($this));
    }

    public function getTable()
    {
        return "users";
    }
}