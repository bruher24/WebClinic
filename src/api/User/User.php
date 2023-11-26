<?php

class User
{
    private DB $db;
    private array $roles = ['users', 'doctors'];

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
        if (!$_SESSION['email']) throw new DataException('Not logged in user can not logout');
        return $this->db->logout($_SESSION['role']);
    }

    public function getUserData(array $data): array
    {
        if ($data['email'] && $data['role']) {
            if (!in_array($data['role'], $this->roles)) throw new DataException("Incorrect role." . " Supported roles are: 'users' and 'doctors'.");
            return $this->db->getUserData($data['role'], $data['email']);
        } elseif ($_SESSION['loggedIn']) return $this->db->getUserData($_SESSION['role'], $_SESSION['email']);
        else throw new DataException('No userdata was given');
    }

    public function setDocData(array $data): array
    {
        $price = $data['price'];
        $lunchTime = $data['lunchTime']; // обед длится 1 час
        if ($_SESSION['loggedIn'] && ($price || $lunchTime)) return $this->db->setDocData($price, $lunchTime);
        throw new DataException();
    }
}