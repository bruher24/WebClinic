<?php

class Application
{
    private DB $db;
    private User $user;

    public function __construct()
    {
        $this->db = new DB;
        $this->user = new User;
    }

    public function register(array $data): string
    {
        if ($data['role']) {
            return $this->user->addUser($data);
        }
        return 'Invalid data[role]'; //TODO: add exception & exception handler
    }

    public function login(array $data): array
    {
        if ($data['email'] && $data['passwordHash']) {
            return $this->user->login($data);
        }
        var_dump('invalid email of pass');
        return array('Invalid data'); //TODO: add exception & exception handler
    }

    public function logout(): array
    {
        if ($_SESSION['email']) {
            return $this->user->logout();
        }
        return array('err');
    }
}