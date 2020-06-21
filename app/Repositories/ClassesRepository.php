<?php


namespace App\Repositories;


use App\Repositories\Stores\ClassesStore;
use Core\Cache\Abstracts\AbstractRepository;

class ClassesRepository extends AbstractRepository {

    public function __construct()
    {
        parent::__construct(new ClassesStore($this));
    }

    public function getFileName()
    {
        return "classes";
    }

}