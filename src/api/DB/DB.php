<?php

class DB
{
    private mysqli $mysqli;
    private User $user;

    public function __construct()
    {
        $this->mysqli = new mysqli('mysql', 'root', 'root', 'WebClinic');
        if ($this->mysqli->connect_error) {
            die('Connect Error (' . $this->mysqli->connect_errno . ') ' . $this->mysqli->connect_error);
        }
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    protected function request(string $queryString, string $paramsAmount, array $inputs, array $results = [], string $successCallback = "success", string $failureCallback = "error"): array
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

    public function addUser($data): array
    {
        if ($data['role'] === 'patient') {
            $data['speciality'] = 'patient';
        }
        $hash = password_hash($data['passwordHash'], PASSWORD_BCRYPT);
        $inputs = [$data['surname'], $data['name'], $data['firstname'], $data['email'], $hash, $data['role'], $data['speciality']];
        return $this->request("INSERT INTO users (surname, name, firstname, email, passwordHash, role, speciality) VALUES (?, ?, ?, ?, ?, ?, ?)", "sssssss", $inputs);
    }

    public function login($data): array
    {
        $stmt = $this->mysqli->prepare("SELECT passwordHash FROM users WHERE email = ?");
        $stmt->bind_param("s", $data['email']);

        if (!$stmt->execute()) {
            return array('not found in db'); //TODO: throw exception
        }

        $stmt->bind_result($hash);
        $stmt->fetch();
        $stmt->close();

        if (password_verify($data['passwordHash'], $hash)) {
            $_SESSION['email'] = $data['email'];
            $_SESSION['sessId'] = session_id();
            $stmt = $this->mysqli->prepare("UPDATE users SET phpsessid = ? WHERE email = ?"); //TODO: разобраться с сессиями
            $stmt->bind_param("ss", $_SESSION['sessId'], $_SESSION['email']);
            if (!$stmt->execute()) {
                return array('add id err');
            }
            $stmt->close();
            $_SESSION['loggedIn'] = true;
            return $_SESSION;
        }
        return array('error');
    }

    public function logout(): array
    {
        $stmt = $this->mysqli->prepare("UPDATE users SET phpsessid = NULL WHERE email = ?");
        $stmt->bind_param("s", $_SESSION['email']);
        if (!$stmt->execute()) {
            return array('logout err');
        }
        $stmt->close();
        $_SESSION = array();
        return array('successfully logged out');
    }

    public function getUserData(): array
    {
        $stmt = $this->mysqli->prepare("SELECT surname, name, firstname, role, speciality FROM users WHERE email = ?");
        $stmt->bind_param("s", $_SESSION['email']);
        if (!$stmt->execute()) {
            return array('getUserData error');
        }
        $stmt->bind_result($userData['surname'], $userData['name'], $userData['firstname'], $userData['role'], $userData['speciality']);
        $stmt->fetch();
        $stmt->close();
        return $userData;
    }
}
