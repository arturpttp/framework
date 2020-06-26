<?php


namespace Core\Cache\Abstracts\Database;


use Closure;
use Core\Cache\Repository;
use Core\Cache\Store;

abstract class DatabaseRepository extends DatabaseCache implements Repository
{

    private $store;

    public function __construct(DatabaseStore $store)
    {
        $this->store = $store;
        parent::__construct();
    }

    public function pull($key, $default = null)
    {
        $value = $this->get($key, $default);
        $this->delete($key);
        return $value;
    }

    public function put($key, $value = null)
    {
        $this->set($key, $value);
    }

    public function add($key, $value = null)
    {
        if (!$this->has($key))
            $this->set($key, $value);
    }

    public function increment($key, $value = 1)
    {
        // TODO: Implement increment() method.
    }

    public function decrement($key, $value = 1)
    {
        // TODO: Implement decrement() method.
    }

    public function forever($key, $value = null)
    {
        $this->set($key, $value);
    }

    public function remember($key, Closure $callback)
    {
        // TODO: Implement remember() method.
    }

    public function sear($key, Closure $callback)
    {
        // TODO: Implement sear() method.
    }

    public function rememberForever($key, Closure $callback)
    {
        // TODO: Implement rememberForever() method.
    }

    public function forget($key)
    {
        $this->delete($key);
    }

    public function getStore(): Store
    {
        return $this->store;
    }

    public function save()
    {
        // TODO: Implement save() method.
    }

    public function load()
    {
        // TODO: Implement load() method.
    }
}