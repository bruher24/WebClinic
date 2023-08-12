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

    public function addUser($data)
    {
        $data['speciality'] = $data['role'] === 'patient' ? 'patient' : null;
        $hash = password_hash($data['passwordHash'], PASSWORD_BCRYPT);
        $stmt = $this->mysqli->prepare("INSERT INTO users (surname, name, firstname, email, passwordHash, role, speciality) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $data['surname'], $data['name'], $data['firstname'], $data['email'], $hash, $data['role'], $data['speciality']);

        if (!$stmt->execute()) {
            return array("err" . " " . $stmt->errno . " " . $stmt->error);
        }
        $stmt->close();
        return "Added successfully";
    }

    public function login($data): array
    {
        $stmt = $this->mysqli->prepare("SELECT passwordHash FROM users WHERE email = ?");
        $stmt->bind_param("s", $data['email']);

        if (!$stmt->execute()) {
            var_dump('execute err');
            return array('not found in db'); //TODO: throw exception
        }

        $stmt->bind_result($hash);
        $stmt->fetch();
        $stmt->close();
        if(password_verify($data['passwordHash'], $hash)) {
            $_SESSION['email'] = $data['email'];
            return $_SESSION;
        }
        return array();
    }
}
