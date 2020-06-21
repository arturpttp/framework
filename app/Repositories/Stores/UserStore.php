<?php


namespace App\Repositories\Stores;


use Core\Cache\Abstracts\AbstractStore;

class UserStore extends AbstractStore
{

    public function getPrefix()
    {
        return "_users";
    }
}