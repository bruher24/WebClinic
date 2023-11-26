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

    public function getUserData(array $data): array
    {
        return $this->user->getUserData($data);
    }

    public function addVisit(array $data): array
    {
        return $this->schedule->addVisit($data);
    }

    public function unsetVisit(array $data): array
    {
        return $this->schedule->unsetVisit($data);
    }

    public function getUserTable(): array
    {
        return $this->schedule->getUserTable();
    }

    public function getDocsTable(array $data): array
    {
        return $this->schedule->getDocsTable($data);
    }

    public function setDocData(array $data): array
    {
        return $this->user->setDocData($data);
    }

    public function makeTimeTable(): array
    {
        return $this->schedule->makeTimeTable();
    }

    public function toolUsageCount(array $data): array
    {
        return $this->schedule->toolUsageCount($data);
    }

    public function getToolsUsage(): array
    {
        return $this->schedule->getToolsUsage();
    }
}
