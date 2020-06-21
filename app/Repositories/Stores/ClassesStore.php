<?php


namespace App\Repositories\Stores;


use Core\Cache\Abstracts\AbstractStore;

class ClassesStore extends AbstractStore {

    public function getPrefix()
    {
        return "classes";
    }
}