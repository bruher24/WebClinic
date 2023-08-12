<?php

class User
{
    private string $surname, $name, $lastname, $email, $password, $role;
    private DB $db;

    public function __construct()
    {
        $this->db = new DB();
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function addUser(array $data): string
    {
        foreach ($data as $item) {
            if (is_null($item)) {
                return 'Invalid data'; //TODO: add exception & exception handler
            }
        }
        return $this->db->addUser($data);
    }

    public function login(array $data): array
    {
        return $this->db->login($data);
    }

}