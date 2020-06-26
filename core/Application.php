<?php


namespace Core;

use App\Repositories\ClassesRepository;
use Core\Cache\Abstracts\AbstractRepository;

class Application
{

    /**
     * Repository for classes
     *
     * @var AbstractRepository
     */
    private $classes;
    /**
     * Application instance
     *
     * @var Application
     */
    private static $instance = null;

    public function __construct()
    {
        $this->classes = new ClassesRepository();
    }

    public function setup()
    {
        $this->registerClasses();
    }

    public function print($message = "undefined print message")
    {
        echo "{$message}";
        return $message;
    }

    public function registerClasses()
    {
        $registrable = [
            'app' => [self::class],
            'app_instance' => [self::getInstance()],
            'db' => [\Core\Database\Database::class, \Core\Database\DB::class],
            'request' => [\Core\System\Request::class],
            'url' => [\Core\System\Url::class],
            'file' => [\Core\System\File::class],
            'session' => [\Core\System\Session::class],
        ];
        $this->classes->setMultiple($registrable);
        $this->classes->save();
        Shadowz::info("Repository '{repository}' loaded and saved", ["repository" => "classes.json"]);
    }

    public static function getInstance()
    {
        if (self::$instance == null || !self::$instance instanceof Application) {
            self::$instance = new self();
            self::$instance->setup();
        }
        return self::$instance;
    }

}