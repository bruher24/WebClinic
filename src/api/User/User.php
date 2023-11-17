<?php

class User
{
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
        try {
            $data['hash'] = password_hash($data['passwordHash'], PASSWORD_BCRYPT);
            $table = $data['role'];
            $params = [$data['surname'], $data['name'], $data['firstname'], $data['email'], $data['hash']];
            if ($table == 'doctors') $params[] = $data['speciality'];
            foreach ($params as $key => $value) {
                if ($value == null) throw new DataException("Все поля должны быть заполнены.");
            }
        } catch (DataException $e) {
            return array('Ошибка! ' . $e->getMessage());
        }
        return $this->db->addUser($data, $table, $params);
    }

    public function login(array $data): array
    {
        if (!$data['email'] || !$data['passwordHash']) throw new DataException();
        if ($_SESSION['loggedIn']) return array('Уже авторизованы');
        return $this->db->login($data);
    }

    public function logout(): array
    {
        if (!$_SESSION['email']) throw new DataException();
        return $this->db->logout($_SESSION['role']);
    }

    public function getUserData(array $data): array
    {
        if (!$data['email']) throw new DataException("Missing user's email");
        return $this->db->getUserData($_SESSION['role'], $data['email']);
    }

    public function addDocData($data): array
    {
        if ($_SESSION['loggedIn'] && $data['speciality'] && $data['price']) return $this->db->addDocData($data);
        throw new DataException();
    }
}