<?php

class Schedule
{
    private DB $db;

    public function __construct()
    {
        $this->db = new DB;
    }

    public function addVisit(array $data): array
    {
        if (!$data['docId'] || !$_SESSION['userId'] || !$data['date'] || !$data['time']) throw new DataException();
        $timeTable = $this->makeTimeTable();
        return $this->db->addVisit($data['docId'], $_SESSION['userId'], $data['date'], $data['time'], $timeTable);
    }

    public function unsetVisit(array $data): array
    {
        if (!$data['date'] || !$data['time']) throw new DataException();
        return $this->db->unsetVisit($data['date'], $data['time']);
    }

    public function getUserTable(): array
    {
        if (!$_SESSION['userId']) throw new DataException();
        $idName = 'user_id';
        if ($_SESSION['role'] == 'doctors') $idName = 'doc_id';
        return $this->db->getUserTable($idName);
    }

    public function getDocsTable(array $data): array
    {
        if (!$data['docId']) throw new DataException('No doc_id given');
        date_default_timezone_set('Europe/Samara');
        $timeTable = $this->makeTimeTable();
        return $this->db->getDocsTable($data['docId'], $timeTable);
    }


    public function makeTimeTable(): array
    {
        date_default_timezone_set('Europe/Samara');
        $startTime = time() + 3600;
        $Date = date('Y-m-d', $startTime);
        for ($i = 0; $i < 31; $i++) {
            if (date('w', strtotime($Date)) != 0 && date('w', strtotime($Date)) != 6) {
                $timeTable[$Date] = '';
            }
            $startTime += 3600 * 24;
            $Date = date('Y-m-d', $startTime);
        }
        if (isset($timeTable)) return $timeTable;
        throw new BaseException();
    }

    public function toolUsageCount(array $data): array
    {
        return $this->db->toolUsageCount($data['speciality'], $data['doc_id']);
    }

    public function getToolsUsage(): array
    {
        return $this->db->getToolsUsage();
    }

}