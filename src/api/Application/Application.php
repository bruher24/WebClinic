<?php

class Application
{
    private DB $db;
    private User $user;
    private Schedule $schedule;

    public function __construct()
    {
        $this->db = new DB;
        $this->user = new User;
        $this->schedule = new Schedule;
    }

    public function register(array $data): array
    {
        foreach ($data as $value) {
            if (is_null($value)) throw new DataException();
        }
        return $this->user->addUser($data);
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
        return array('No email');
    }

    public function getUserData(): array
    {
        return $this->user->getUserData();
    }

    public function addVisit($data): array
    {
        return $this->schedule->addVisit($data);
    }

    public function unsetVisit($data): array
    {
        return $this->schedule->unsetVisit($data);
    }

    public function getUserTable(): array
    {
        return $this->schedule->getUserTable();
    }

    public function getDocsTable($params): array
    {
        return $this->schedule->getDocsTable($params);
    }
}