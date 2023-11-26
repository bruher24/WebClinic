<?php

//TODO: изменить все методы под новый концепт

require_once 'connect.php';

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

    private function request(string $query, string $types = null, $params = null): array
    {
        $noResultTypes = ['DELETE', 'UPDATE', 'INSERT'];
        $queryType = mb_substr($query, 0, 6);
        $stmt = $this->mysqli->prepare($query);
        if (is_array($params)) $stmt->bind_param($types, ...$params);
        elseif ($params !== null) $stmt->bind_param($types, $params);
        if (!$stmt->execute()) return array($stmt->error);
        $result = $stmt->get_result();
        if (!$result) {
            if (mysqli_stmt_errno($stmt) === 000) {
                if (in_array($queryType, $noResultTypes)) {
                    return array('ok');
                }
            }
            return array($stmt->error);
        }

        $answer = [];
        while ($row = $result->fetch_assoc()) $answer[] = $row;
        $stmt->close();
        return $answer;
    }

    public function addUser(array $data, string $table, array $params): array
    {
        $userCheck = $this->request("SELECT `email` FROM `{$table}` WHERE `email` = ?", "s", $data['email']);
        if ($userCheck) return array('Данный email уже зарегистрирован');
        if ($table == 'doctors')
            $result = $this->request("INSERT INTO `{$table}` (`surname`, `name`, `firstname`, `email`, `passwordHash`, `speciality`) VALUES (?, ?, ?, ?, ?, ?)", "ssssss", $params);
        else
            $result = $this->request("INSERT INTO `{$table}` (`surname`, `name`, `firstname`, `email`, `passwordHash`) VALUES (?, ?, ?, ?, ?)", "sssss", $params);
        if ($result[0] == 'ok') return array('Успешная регистрация');
        throw new BaseException();
    }

    public function login(array $data): array
    {
        $table = $this->userExistCheck($data);
        if ($table == null) {
            return array('Пользователь не существует');
        }

        $hash = $this->request("SELECT `passwordHash` FROM `{$table}` WHERE `email` = ?", "s", $data['email'])[0]['passwordHash'];

        if (password_verify($data['passwordHash'], $hash)) {
            $_SESSION['email'] = $data['email'];
            $_SESSION['sessId'] = session_id();
            $params = [$_SESSION['sessId'], $_SESSION['email']];
            $this->request("UPDATE `{$table}` SET `phpsessid` = ? WHERE `email` = ?", "ss", $params);
            $userData = $this->getUserData($table, $data['email']);
            $_SESSION['loggedIn'] = true;
            $_SESSION['userId'] = $userData['user_id'] ?? $userData['doc_id'];
            $_SESSION['role'] = $table;
            return array('Успешный вход');
        }
        return array('Неверный пароль');
    }

    public function logout(string $table): array
    {
        $this->request("UPDATE `{$table}` SET `phpsessid` = NULL WHERE `email` = ?", "s", $_SESSION['email']);
        $_SESSION = array();
        session_destroy();
        return array('Успешный выход');
    }

    public function userExistCheck(array $data): ?string
    {
        $result = $this->request("SELECT `email` FROM `doctors` WHERE `email` = ?", "s", $data['email']);
        if (!$result[0]) {
            $result = $this->request("SELECT `email` FROM `users` WHERE `email` = ?", "s", $data['email']);
            if (!$result[0]) {
                return null;
            }
            return 'users';
        }
        return 'doctors';
    }

    public function setDocData(int $price = null, string $lunchTime = null): array
    {
        if (isset($price)) {
            if ($this->request("UPDATE `doctors` SET `price` = {$price} WHERE `email` = ?", "s", $_SESSION['email'])) $result[] = ('Стоимость успешно установлена.');
            else throw new BaseException();
        }
        if (isset($lunchTime)) {
            if ($this->request("UPDATE `doctors` SET `lunch_time` = '{$lunchTime}' WHERE `email` = ?", "s", $_SESSION['email'])) $result[] = ('Обеденное время задано.');
            else throw new BaseException();
        }
        if (isset($result)) return $result;
        else throw new BaseException();
    }

    public function getUserData(string $table, string $email = null, int $id = null): array
    {
        $idName = $table == 'doctors' ? 'doc_id' : 'user_id';
        if ($id) return $this->request("SELECT * FROM `{$table}` WHERE `{$idName}` = ?", "i", $id)[0];
        if ($email) return $this->request("SELECT * FROM `{$table}` WHERE `email` = ?", "s", $email)[0];
        throw new DataException('No email or id given');
    }

    public function addVisit(int $docId, int $userId, string $date, string $time, array $table): array
    {
        $docData = $this->getUserData('doctors', null, $docId);
        $userData = $this->getUserData('users', null, $userId);
        $docTable = $this->getDocsTable($docId, $table);
        if (array_key_exists($date, $docTable)) {
            if (in_array($time . ":00", $docTable[$date])) {
                $params = [$date, $time, $docId, $docData['surname'] . " " . $docData['name'] . " " . $docData['firstname'], $docData['speciality'],
                    $userId, $userData['surname'] . " " . $userData['name'] . " " . $userData['firstname'], $docData['price']];
                $this->request("INSERT INTO `schedule`(`date`, `time`, `doc_id`, `doc_name`, `speciality`, `user_id`, `user_name`, `price`) VALUES (?, ?, ?, ?, ?, ?, ?, ?);", "ssissisi", $params);
                return array("Успешная запись на {$date} в {$time}.");
            } else return array("Время занято. Пожалуйста, выберите другое время.");
        } else return array("Неприемный день. Пожалуйста, выберите другой день.");
    }


    public function unsetVisit(string $date, string $time): array
    {
        $params = [$date, $time, $_SESSION['userId']];
        $checkVisit = $this->request("SELECT * FROM `schedule` WHERE `date` = ? AND `time` = ? AND `user_id` = ?", "ssi", $params)[0];
        if (!$checkVisit) return array('Запись не найдена');
        $deleteQuery = $this->request("DELETE FROM `schedule` WHERE `date` = ? AND `time` = ? AND `user_id` = ?", "ssi", $params)[0];
        if ($deleteQuery == 'ok') return array("Запись на {$date} в {$time} успешно удалена.");
        throw new BaseException();
    }

    public function getUserTable(string $idName): array
    {
        return $this->request("SELECT `date`, `time`, `doc_name`, `speciality`, `user_name`, `price` FROM `schedule` WHERE `{$idName}` = ?", "i", $_SESSION['userId']);
    }


    public function getDocsTable(int $docId, array $table): array
    {
        $timeTable = [];
        $dbTimeTable = $this->request("SELECT `time` FROM `timetable`");
        $lunchTime = $this->request("SELECT `lunch_time` FROM `doctors` WHERE `doc_id` = ?", "i", $docId)[0];
        foreach ($dbTimeTable as $key => $value) {
            $timeTable[] = $value['time'];
        }

        $i = 0;
        while ($timeTable[$i]) {
            if ($timeTable[$i] === $lunchTime['lunch_time'] . ':00') {
                unset($timeTable[$i]);
                unset($timeTable[$i + 1]);
            }
            $i++;
        }

        foreach ($table as $key => $value) {
            $table[$key] = $timeTable;
        }

        $docVisits = $this->request("SELECT `date`, `time` FROM `schedule` WHERE `doc_id` = ?", "i", $docId);
        foreach ($table as $key => $value) {
            foreach ($docVisits as $item) {
                if ($item['date'] == $key) {
                    foreach ($value as $index => $var) {
                        if ($var == $item['time']) unset($table[$key][$index]);
                    }
                }
            }
        }
        return $table;
    }

    public function getVisits(int $userId, string $idName): array
    {
        return $this->request("SELECT `date`, `time`, `doc_name`, `speciality`, `user_name`, `price` FROM `schedule` WHERE `{$idName}` = ?", "i", $userId);
    }

    public function toolUsageCount(string $speciality, int $docId): array
    {
        $result = [];
        $select = $this->request("SELECT `tools`, `base_tools` FROM `specialities` WHERE `speciality` = ?", "s", $speciality);
        $tools = explode(', ', $select[0]['tools']);
        $baseTools = explode(', ', $select[0]['base_tools']);
        $toolsList = array_merge($tools, $baseTools);
        $visitsAmount = count($this->getVisits($docId, 'doc_id'));
        $usageTimeList = $this->request("SELECT * FROM `tools`");
        foreach ($toolsList as &$item) {
            foreach ($usageTimeList as $key => $value) {
                if ($item == $value['tool_name']) $item = [$item => $value['using_time']];
            }
        }
        foreach ($toolsList as $arr) {
            foreach ($arr as $key => $var) {
                $visitsTime = $visitsAmount * 30;
                $usageTime = mb_substr($var, 3, 2);
                if ($var[0] != '0' || $var[1] != '0') {
                    $hoursStr = mb_substr($var, 0, 2);
                    $usageTime = $hoursStr * 60;
                }
                $toolsAmount = $visitsTime / $usageTime;
                $result[$key] = ceil($toolsAmount);
            }
        }
        $docData = $this->getUserData('doctors', null, $docId);
        foreach ($result as $key => $value) {
            $params = [$docData['surname'] . " " . $docData['name'] . " " . $docData['firstname'], $docData['speciality'], $key, $value];
            $insertQuery = $this->request("INSERT INTO `tools_usage`(`doc_name`, `speciality`, `tool_name`, `used_amount`) VALUES (?, ?, ?, ?)", "sssi", $params);
        }
        return $result;
    }

    public function getToolsUsage(): array
    {
        return $this->request("SELECT * FROM `tools_usage`");
    }
}
