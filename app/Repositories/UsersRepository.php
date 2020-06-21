<?php


namespace App\Repositories;


use App\Repositories\Stores\UserStore;
use Core\Cache\Abstracts\AbstractRepository;
use Core\Cache\Abstracts\AbstractStore;

class UsersRepository extends AbstractRepository
{

    public function __construct()
    {
        parent::__construct(new UserStore($this));
    }

    public function getFileName()
    {
        return "users";
    }
}