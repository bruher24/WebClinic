<?php

class Application
{
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
        return $this->user->addUser($data);
    }

    public function login(array $data): array
    {
        return $this->user->login($data);
    }

    public function logout(): array
    {
        return $this->user->logout();
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

    public function getDocsTable($data): array
    {
        return $this->schedule->getDocsTable($data);
    }

    public function addDocData($data): array
    {
        return $this->user->addDocData($data);
    }
}