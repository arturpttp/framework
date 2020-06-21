<?php


namespace Core;

use App\Repositories\ClassesRepository;
use Core\Cache\Abstracts\AbstractRepository;
use Core\Essetials\System;

class Application
{

    use System;

    /**
     * @var AbstractRepository
     */
    private $classes;

    public function __construct()
    {
        $this->classes = new ClassesRepository();
    }

    public function setup()
    {
        $this->registerClasses();
    }

    public function registerClasses()
    {
        $registrable = [
            'db' => [\Core\Database\Database::class, \Core\Database\DB::class],
            'request' => [\Core\Essetials\Request::class],
            'url' => [\Core\Essetials\Url::class],
            'file' => [\Core\Essetials\File::class],
            'session' => [\Core\Essetials\Session::class],
        ];
        $this->classes->setMultiple($registrable);
        $this->classes->save();
    }

}