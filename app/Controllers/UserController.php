<?php


namespace App\Controllers;


use App\Models\UserModel;
use App\Repositories\UsersRepository;
use Core\Bases\Controller;
use Core\System\Request;
use Core\System\System;
use Core\System\Validator;
use Core\User\User;

class UserController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->model = new UserModel();
    }

    public function login()
    {
//        $userObj->changeLevel(1);
    }

    public function index() {
        //echo "DEBUGGING";
    }

    public function register()
    {
        $this->setTitle("User Registration");
        $this->renderView("user/register");
    }

    public function profile($id)
    {
        if (!User::userExists($id))
            return $this->addError("User with id {$id} doesn't exists");
        $user = $this->model->getProfile($id);
        $this->app->print($user->name);
    }

    public function registerSubmit($request)
    {

        $data = [
            'name' => [
                'display' => 'Name',
                'required' => true
            ],
            'email' => [
                'display' => 'Email',
                'required' => true,
                'unique' => 'email'
            ],
            'user' => [
                'display' => 'User',
                'min' => 8,
                'required' => true,
                'unique' => 'user'
            ],
            'password' => [
                'display' => 'Password',
                'required' => true,
                'min' => 6,
                'match' => 'confirm_password'
            ],
            'confirm_password' => [
                'display' => 'Confirm Password',
                'required' => true,
                'min' => 6
            ],
        ];
        $validator = new Validator;
        if ($validator->make($data, $this->model)->isPassed()) {
            $name = Request::post('name');
            $user = Request::post('user');
            $email = Request::post('email');
            $password = Request::post('password');

            $userObj = new User($this->model);
            $userObj->create($name, $user, $email, $password);
        }else {
            $errors = array_values($validator->errors());
            System::redirect("/user/register", ['errors' => $errors]);
        }

    }

}