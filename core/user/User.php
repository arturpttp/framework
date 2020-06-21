<?php


namespace Core\User;


use App\Models\UserModel;
use Core\Essetials\Model;
use Core\Essetials\Request;
use Core\Essetials\Session;

class User implements UserInterface
{

    public $id;
    public $name;
    public $user;
    public $email;
    public $password;
    public $level;
    private $model;
    private static $logged = false;
    public static $levels = [
        "default" => '0',
        "user" => '1',
        "moderator" => '2',
        "admin" => '3',
        "owner" => '4'
    ];

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    function load(): User
    {
        $query = $this->model->query("SELECT * FROM {$this->model->table} WHERE user=? AND password=?", [
            'user' => $this->user,
            'password' => $this->password
        ], true);
        $data = $query->first();
        pre($data);
        $this->id = $data["id"];
        $this->name = $data["name"];
        $this->email = $data["email"];
        $this->password = $data["password"];
        $this->level = $data["level"];
        Session::set('loggedUser', $this->id);
        return $this;
    }

    public function exists(): bool
    {
        return $this->model->contains('id', $this->id);
    }

    public function create($name, $user, $email, $password, $level = 1): User
    {
        $data = [
            'name' => $name,
            'user' => $user,
            'email' => $email,
            'password' => $password,
            'level' => $level,
        ];
        $this->model->insert($data);
        $this->login($user, $password);
        return $this;
    }

    public function changeLevel($level): User
    {
        $this->level = $level;
        $where = ['id' => $this->id];
        $data = ['level' => $level];
        $this->model->update($data, $where);
        return $this;
    }

    public function delete(): User
    {
        $where = ['id', $this->id];
        $this->model->delete($where);
        return $this;
    }

    public static function isLogged(): bool
    {
        return static::$logged;
    }

    public function login($user, $password): User
    {
        $this->user = $user;
        $this->password = $password;
        static::$logged = true;
        return $this->load();
    }

    public function logout(): User
    {
        $this->id = null;
        $this->name = null;
        $this->user = null;
        $this->email = null;
        $this->password = null;
        $this->level = null;
        $this->model = new UserModel();
        static::$logged = false;
        return $this;
    }
}