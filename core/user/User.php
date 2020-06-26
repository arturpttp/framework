<?php


namespace Core\User;


use App\Models\UserModel;
use Core\Container;
use Core\Bases\Model;
use Core\System\Request;
use Core\System\Session;

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
    private static $loggedUser = false;
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
        $query = $this->model->find(['user' => $this->user, 'password' => $this->password]);
        $data = $query->first();
        $this->id = $data["id"];
        $this->name = $data["name"];
        $this->email = $data["email"];
        $this->password = $data["password"];
        $this->level = $data["level"];
        Session::set('loggedUser', $this->id);
        self::$loggedUser = $this;
        return $this;
    }

    public static function get($id)
    {
        $model = new UserModel();
        if (!$model->contains('id', $id))
            return null;
        $user = new User($model);
        $data = $user->model->find(['id' => $id])->first();
        $user->id = $data["id"];
        $user->name = $data["name"];
        $user->email = $data["email"];
        $user->password = $data["password"];
        $user->level = $data["level"];
        return $user;
    }

    public static function userExists($id): bool
    {
        return resumeIf(self::get($id) == null, false, true);
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
        if ($this->level == $level)
            return $this;
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
        self::$loggedUser = false;
        return $this;
    }

    /**
     * Returns logged user, or returns false if isn't user logged
     *
     * @return User|bool
     */
    public static function getLoggedUser() {
        return self::$loggedUser;
    }
}