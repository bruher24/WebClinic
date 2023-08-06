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

    public function signUp(array $data): array
    {
        if ($data['role']) {
            return $this->user->addUser($data);
        }
        return array('Msg' => 'Invalid data[role].'); //TODO: add exception & exception handler
    }
}