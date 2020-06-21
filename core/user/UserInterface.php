<?php


namespace Core\User;


interface UserInterface
{

    function load(): User;
    public function exists(): bool;
    public function create($name, $user, $email, $password, $level = 1): User;
    public function changeLevel($level): User;
    public function delete(): User;
    public function login($user, $password): User;
    public function logout(): User;

}