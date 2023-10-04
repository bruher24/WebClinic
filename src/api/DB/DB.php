<?php

use Cassandra\Date;

class DB
{
    private mysqli $mysqli;

    public function __construct()
    {
        $this->mysqli = new mysqli('mysql', 'root', 'root', 'WebClinic');
        $this->mysqli->set_charset('utf8mb4');
        $this->mysqli->query("SET NAMES `utf8mb4`");
        $this->mysqli->query("SET CHARACTER SET `utf8mb4`");
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
        $params = [$data['surname'], $data['name'], $data['firstname'], $data['email'], $data['hash'], $data['role'], $data['speciality'], $data['price']];
        $userCheck = $this->request("SELECT `user_id` FROM `users` WHERE `email` = ?", "s", $data['email']);
        if ($userCheck) return array('Данный email уже зарегистрирован');
        $result = $this->request("INSERT INTO `users` (`surname`, `name`, `firstname`, `email`, `passwordHash`, `role`, `speciality`, `price`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            "ssssssss", $params);
        if ($result[0] == 'ok') return array('Успешная регистрация');
        return array('error');
    }

    public function login(array $data): array
    {
        $hash = $this->request("SELECT `passwordHash` FROM `users` WHERE `email` = ?", "s", $data['email'])[0]['passwordHash'];
        if (password_verify($data['passwordHash'], $hash)) {
            $_SESSION['email'] = $data['email'];
            $_SESSION['sessId'] = session_id();
            $params = [$_SESSION['sessId'], $_SESSION['email']];
            $this->request("UPDATE `users` SET `phpsessid` = ? WHERE `email` = ?", "ss", $params);
            $userData = $this->getUserData()[0];
            $_SESSION['loggedIn'] = true;
            $_SESSION['userId'] = $userData['user_id'];
            return array('Успешный вход');
        }
        return array('Неверный пароль');
    }

    public function logout(): array
    {
        $this->request("UPDATE `users` SET `phpsessid` = NULL WHERE `email` = ?", "s", $_SESSION['email']);
        $_SESSION = array();
        session_destroy();
        return array('Успешный выход');
    }

    public function getUserData(int $id = null, string $email = null): array
    {
        if ($id !== null) $userData = $this->request("SELECT `user_id`, `surname`, `name`, `firstname`, `role`, `speciality`, `price` FROM `users` WHERE `user_id` = ?", "i", $id);
        elseif ($email !== null) $userData = $this->request("SELECT `user_id`, `surname`, `name`, `firstname`, `role`, `speciality`, `price` FROM `users` WHERE `email` = ?", "s", $email);
        else $userData = $this->request("SELECT `user_id`, `surname`, `name`, `firstname`, `role`, `speciality`, `price` FROM `users` WHERE `email` = ?", "s", $_SESSION['email']);
        return $userData;
    }

    public function addVisit(int $docId, int $userId, string $dateTime): array
    {
        $docData = $this->getUserData($docId)[0];
        $result = $this->request("SELECT `doc_id` FROM `schedule` WHERE `datetime` = ?;", "s", $dateTime);

        if ($result[0]['doc_id'] === $docId) return array("Занято");

        $params = [$dateTime, $docId, $userId, $docData['price']];
        $this->request("INSERT INTO `schedule`(`datetime`, `doc_id`, `user_id`, `price`) VALUES (?, ?, ?, ?);", "siii", $params);
        return array("Успешная запись на " . $dateTime);
    }


    public function unsetVisit(string $datetime): array
    {
        $params = [$datetime, $_SESSION['userId']];
        $delete = $this->request("DELETE FROM schedule WHERE `datetime` = ? AND `user_id` = ?", "si", $params);
        if ($delete[0] === 'ok') return array('Запись на ' . $datetime . ' успешно удалена.');
        return array('Запись на ' . $datetime . ' не найдена.');
    }

    public function getUserTable(): array
    {
        $select = $this->request("SELECT * FROM `schedule` WHERE `user_id` = ?", "i", $_SESSION['userId']);
        $result = [];
        foreach ($select as $array) {
            $table['datetime'] = $array['datetime'];
            $docData = $this->request("SELECT `surname`, `name`, `firstname`, `speciality` FROM `users` WHERE `user_id` = ?", "i", $array['doc_id'])[0];
            $table['docName'] = $docData['surname'] . ' ' . $docData['name'] . ' ' . $docData['firstname'];
            $table['docSpeciality'] = $docData['speciality'];
            $table['price'] = $array['price'];
            $result[] = $table;
        }
        return $result;
    }


    public function getDocsTable(int $docId, array $table): array
    {
        $result = [];
        foreach ($table as $value) {
            $params = [$value, $docId];
            $select = $this->request("SELECT `price` from `schedule` WHERE `datetime` = ? AND `doc_id` = ?", "si", $params)[0];
            if (!$select) $result[] = $value;
        }
        return $result;
    }


    private function request(string $query, string $types = null, $params = null): array
    {
        $stmt = $this->mysqli->prepare($query);
        if (is_array($params)) $stmt->bind_param($types, ...$params);
        elseif ($params !== null) $stmt->bind_param($types, $params);
        if (!$stmt->execute()) return array($stmt->error);
        $result = $stmt->get_result();
        if (!$result) return mysqli_stmt_errno($stmt) === 000 ? array('ok') : $stmt->error;
        $answer = [];
        while ($row = $result->fetch_assoc()) $answer[] = $row;
        $stmt->close();
        return $answer;
    }


}
