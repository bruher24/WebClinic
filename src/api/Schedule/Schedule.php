<?php

class Schedule
{
    private DB $db;

    public function __construct()
    {
        $this->db = new DB;
    }

    public function addVisit($data): array
    {
        if (!$data['docId'] || !$_SESSION['userId'] || !$data['dateTime']) throw new DataException();
        return $this->db->addVisit($data['docId'], $_SESSION['userId'], $data['dateTime']);
    }

    public function unsetVisit($data): array
    {
        if (!$data['dateTime']) throw new DataException();
        return $this->db->unsetVisit($data['dateTime']);
    }

    public function getUserTable(): array
    {
        if (!$_SESSION['userId']) throw new DataException();
        return $this->db->getUserTable();
    }

    public function getDocsTable($data): array
    {
        if (!$data['day'] || !$data['docId']) throw new DataException();
        date_default_timezone_set('Europe/Samara');
        $datetime = new DateTime($data['day']);
        if ($datetime->format('N') == 6 || $datetime->format('N') == 7) return array('Выходной. Записи нет.');
        $table = $this->timeTable($data['day']);

        return $this->db->getDocsTable($data['docId'], $table);
    }


    public function timeTable(string $day): array
    {
        $time = new \DateTime('09:00');
        $endOfDay = new \DateTime('17:30');
        $table = [];
        while ($time <= $endOfDay) {
            $table[] = $day . ' ' . $time->format('H:i');
            $time->add(new \DateInterval('PT30M'));
        }
        return $table;
    }

}