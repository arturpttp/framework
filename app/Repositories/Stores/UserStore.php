<?php


namespace App\Repositories\Stores;


use Core\Cache\Abstracts\AbstractStore;
use Core\Cache\Abstracts\Database\DatabaseStore;

class UserStore extends DatabaseStore {

    public function getPrefix()
    {
        return "_users";
    }
}