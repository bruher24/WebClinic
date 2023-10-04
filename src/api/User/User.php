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

    public function addUser(array $data): array
    {
        foreach ($data as $item) {
            if (is_null($item)) throw new DataException();
        }
        $data['speciality'] = $data['role'] === 'patient' ? 'patient' : $data['speciality'];
        $data['price'] = $data['price'] ?? 0;
        $data['hash'] = password_hash($data['passwordHash'], PASSWORD_BCRYPT);
        return $this->db->addUser($data);
    }

    public function login(array $data): array
    {
        if ($_SESSION['loggedIn']) return array('Уже авторизованы');
        return $this->db->login($data);
    }

    public function logout(): array
    {
        return $this->db->logout();
    }

    public function getUserData(): array
    {
        return $this->db->getUserData();
    }

}