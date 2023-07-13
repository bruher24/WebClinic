<?php
class DB
{
    private mysqli $mysqli;
    public function __construct()
    {
        $this->mysqli = new mysqli('mysql', 'root', 'root', 'WebClinic');
    }

    public function addDoctor($data): array
    {
        $insert = "INSERT INTO doctors (surname, name, firstname, email, passwordHash, speciality) VALUES ('$data[surname]', '$data[name]', '$data[firstname]', '$data[email]', '$data[passwwordHash]', '$data[speciality]')";
        return $this->mysqli->query($insert)->fetch_assoc();
    }

    public function addPatient($data): array
    {
        $insert = "INSERT INTO patients (surname, name, firstname, email, passwordHash) VALUES ('$data[surname]', '$data[name]', '$data[firstname]', '$data[email]', '$data[passwwordHash]')";
        return $this->mysqli->query($insert)->fetch_assoc();
    }
}


