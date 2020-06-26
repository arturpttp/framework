<?php


namespace Core\User;


interface UserInterface
{

    /**
     * load user information
     *
     * @return User
     */
    function load(): User;

    /**
     * get user by $id, returns null if doesn't exists
     *
     * @param $id
     * @return User|null
     */
    public static function get($id);

    /**
     * check if user exists by $id
     *
     * @param $id
     * @return bool
     */
    public static function userExists($id): bool;

    /**
     * check if logged user really exists
     *
     * @return bool
     */
    public function exists(): bool;

    /**
     * create an user
     *
     * @param $name
     * @param $user
     * @param $email
     * @param $password
     * @param int $level
     * @return User
     */
    public function create($name, $user, $email, $password, $level = 1): User;

    /**
     * change $level from logged user
     *
     * @param $level
     * @return User
     */
    public function changeLevel($level): User;

    /**
     * delete logged user
     *
     * @return User
     */
    public function delete(): User;

    /**
     * log in a user with $user and $password
     *
     * @param $user
     * @param $password
     * @return User
     */
    public function login($user, $password): User;

    /**
     * log out the logged user
     *
     * @return User
     */
    public function logout(): User;

}