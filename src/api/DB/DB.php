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

    public function request(string $queryString, string $paramsAmount, array $inputs, string $failureCallback, array $results, string $successCallback): string
    {
        $stmt = $this->mysqli->prepare($queryString);
        $stmt->bind_param($paramsAmount, ...$inputs);
        if (!$stmt->execute()) {
            $stmt->close();
            return $failureCallback;
        }
        if (strstr($queryString, 'SELECT', 0)) {
            $stmt->bind_result(...$results);
            $stmt->fetch();
            $stmt->close();
        }
        return $successCallback;
    }

    public function addUser($data): string
    {
        $data['speciality'] = $data['role'] === 'patient' ? 'patient' : null;
        $hash = password_hash($data['passwordHash'], PASSWORD_BCRYPT);
        $stmt = $this->mysqli->prepare("INSERT INTO users (surname, name, firstname, email, passwordHash, role, speciality) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $data['surname'], $data['name'], $data['firstname'], $data['email'], $hash, $data['role'], $data['speciality']);

        if (!$stmt->execute()) {
            return "err" . " " . $stmt->errno . " " . $stmt->error;
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
        if (password_verify($data['passwordHash'], $hash)) {
            $_SESSION['email'] = $data['email'];
            $stmt = $this->mysqli->prepare("UPDATE users SET phpsessid = ? WHERE email = ?");
            //TODO: разобраться с сессиями
            $_SESSION['sessId'] = session_id();
            $stmt->bind_param("ss", $_SESSION['sessId'], $_SESSION['email']);
            if (!$stmt->execute()) {
                var_dump('add id err');
                return array('add id err');
            }
            $stmt->close();
            return $_SESSION;
        }
        return array('error');
    }

    public function logout(): array
    {
        $stmt = $this->mysqli->prepare("UPDATE users SET phpsessid = NULL WHERE email = ?");
        $stmt->bind_param("s", $_SESSION['email']);
        if (!$stmt->execute()) {
            var_dump('logout err');
            return array('logout err');
        }
        $stmt->close();
        var_dump('succ logout');
        $_SESSION['email'] = null;
        return array('successfully logged out');
    }
}
