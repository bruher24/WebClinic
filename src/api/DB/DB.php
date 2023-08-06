<?php

class DB
{
    private mysqli $mysqli;

    public function __construct()
    {
        $this->mysqli = new mysqli('mysql', 'root', 'root', 'WebClinic');
        if ($this->mysqli->connect_error) {
            die('Connect Error (' . $this->mysqli->connect_errno . ') ' . $this->mysqli->connect_error);
        }
    }

    public function addUser($data):array
    {
        $data['speciality'] = $data['role'] === 'patient' ? 'patient' : null;
        if ($data['role'] === 'doctor') {
            $stmt = $this->mysqli->prepare("INSERT INTO doctors (surname, name, firstname, email, passwordHash, speciality) VALUES (?, ?, ?, ?, ?, ?)");
        }else {
            $stmt = $this->mysqli->prepare("INSERT INTO patients (surname, name, firstname, email, passwordHash, speciality) VALUES (?, ?, ?, ?, ?, ?)");
        }
        $stmt->bind_param("ssssss",$data['surname'], $data['name'], $data['firstname'], $data['email'], $data['passwordHash'], $data['speciality']);

        if (!$stmt->execute()){
            return array("err". " " . $stmt->errno . " " . $stmt->error);
        }
        return array("1" => "Added successfully");
    }
}
