<?php


namespace App\Repositories\Stores;


use Core\Cache\Abstracts\AbstractStore;

class LogStore extends AbstractStore
{

    public function getPrefix()
    {
        return "__log";
    }
}