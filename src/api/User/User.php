<?php



class User
{
    private string $surname, $name, $lastname, $email, $password, $role;
    private DB $db;

    public function __construct()
    {
        $this->db = new DB();
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function addDoctor(array $data): array
    {
        foreach ($data as $item) {
            if (is_null($item)) {
                return array('Msg' => 'Invalid data.'); //TODO: add exception & exception handler
            }
        }
        return $this->db->addDoctor($data);
    }

    public function addPatient(array $data): array
    {
        foreach ($data as $item) {
            if (is_null($item) && $item !== 'speciality') {
                return array('Msg' => 'Invalid data.'); //TODO: add exception & exception handler
            }
        }
        return $this->db->addPatient($data);
    }
}