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
        return $this->db->addVisit($data['docId'], $_SESSION['userId'], $data['dateTime']);
    }

    public function unsetVisit($data): array
    {
        return $this->db->unsetVisit($data['dateTime']);
    }

    public function getUserTable(): array
    {
        return $this->db->getUserTable();
    }

    public function getDocsTable($params): array
    {
        date_default_timezone_set('Europe/Kirov');
        $datetime = new DateTime($params['day']);
        if ($datetime->format('N') == 6 || $datetime->format('N') == 7) return array('Выходной. Записи нет.');
        $table = $this->timeTable($params['day']);

        return $this->db->getDocsTable($params['docId'], $table);
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