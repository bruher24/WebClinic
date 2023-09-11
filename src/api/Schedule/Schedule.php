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

}