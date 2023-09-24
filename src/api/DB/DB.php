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

    public function __set(string $name, $value)
    {
        $this->$name = $value;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }


    public function addUser(array $data): array
    {
        if ($data['role'] === 'patient') {
            $data['speciality'] = 'patient';
        }
        $hash = password_hash($data['passwordHash'], PASSWORD_BCRYPT);
        $params = [$data['surname'], $data['name'], $data['firstname'], $data['email'], $hash, $data['role'], $data['speciality']];
        return $this->request("INSERT INTO `users` (`surname`, `name`, `firstname`, `email`, `passwordHash`, `role`, `speciality`) VALUES (?, ?, ?, ?, ?, ?, ?)",
            "sssssss", $params);
    }

    public function login(array $data): array
    {
        $hash = $this->request("SELECT `passwordHash` FROM `users` WHERE `email` = ?", "s", $data['email'])['passwordHash'];
        if (password_verify($data['passwordHash'], $hash)) {
            $_SESSION['email'] = $data['email'];
            $_SESSION['sessId'] = session_id();
            $params = [$_SESSION['sessId'], $_SESSION['email']];
            $this->request("UPDATE `users` SET `phpsessid` = ? WHERE `email` = ?", "ss", $params);
            $userData = $this->getUserData();
            $_SESSION['loggedIn'] = true;
            $_SESSION['userId'] = $userData['user_id'];
            return $_SESSION;
        }
        return array('Неверный пароль');
    }

    public function logout(): array
    {
        $this->request("UPDATE `users` SET `phpsessid` = NULL WHERE `email` = ?", "s", $_SESSION['email']);
        $_SESSION = array();
        return array('Успешный выход');
    }

    public function getUserData(int $id = null): array
    {
        if ($id !== null) $userData = $this->request("SELECT `user_id`, `surname`, `name`, `firstname`, `role`, `speciality`, `price` FROM `users` WHERE `user_id` = ?", "i", $id);
        else $userData = $this->request("SELECT `user_id`, `surname`, `name`, `firstname`, `role`, `speciality`, `price` FROM `users` WHERE `email` = ?", "s", $_SESSION['email']);
        return $userData;
    }

    public function addVisit(int $docId, int $userId, string $dateTime): array
    {
        $docData = $this->getUserData($docId);
        $result = $this->request("SELECT `doc_id` FROM `schedule` WHERE `datetime` = ?;", "s", $dateTime);
        if ($result['doc_id'] === $docId) return array("Занято");

        $params = [$dateTime, $docId, $userId, $docData['price']];
        $this->request("INSERT INTO `schedule`(`datetime`, `doc_id`, `user_id`, `price`) VALUES (?, ?, ?, ?);", "siii", $params);
        return array("Успешная запись на " . $this->dateConvert($dateTime));
    }

    public function dateConvert(string $date): string
    {
        return substr($date, 0, 4) . "-" . substr($date, 4, 2) . "-" . substr($date, 6, 2) . " " . substr($date, 8, 2) . ":" . substr($date, 10, 2);
    }

    public function unsetVisit(string $datetime): array //TODO:выдает успешное удаление, даже когда не удалил, см. $this->request();
    {
        $result = $this->request("DELETE FROM schedule WHERE `datetime` = ?", "s", $datetime);
        if ($result[0] === 'ok') return array('Запись на ' . $datetime . ' успешно удалена.');
        return array('Запись на ' . $datetime . ' не найдена.');
    }

    private function request(string $query, string $types = null, $params = null): array
    {
        $stmt = $this->mysqli->prepare($query);
        if (is_array($params)) $stmt->bind_param($types, ...$params);
        elseif ($params !== null) $stmt->bind_param($types, $params);
        if (!$stmt->execute()) return array($stmt->error);
        $result = $stmt->get_result();
        //resultHandler using
        if (!$result) return mysqli_stmt_errno($stmt) === 000 ? array('ok') : $stmt->error;
        $answer = [];
        while ($row = $result->fetch_assoc()) $answer[] = $row;
        //
        $stmt->close();
        return $answer[0];
    }

    private function resultHandler(string $query, $result)
    {
        $method = explode(' ', $query)[0];
        switch ($method) {
            case 'SELECT':
                if($result instanceof mysqli_result) {
                    $answer = [];
                    while ($row = $result->fetch_assoc()) $answer[] = $row;
                    return $answer[0];
                }
                return mysqli_stmt_error();
            case 'INSERT':
                return 123;
            case 'UPDATE':
                return 123;
            case 'DELETE':
                return 123;
        }
    }


}
