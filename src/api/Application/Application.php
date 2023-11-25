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

    public function setDocPrice(array $data): array
    {
        return $this->user->setDocPrice($data);
    }

    public function makeTimeTable(): array
    {
        return $this->schedule->makeTimeTable();
    }

    public function toolUsageCount($data): array
    {
        return $this->schedule->toolUsageCount($data);
    }
    public function getToolsUsage(): array
    {
        return $this->db->getToolsUsage();
    }
}