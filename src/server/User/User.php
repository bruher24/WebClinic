<?php
class User
{
    private string $surname, $name, $lastname, $email, $password, $role;
    public function __construct($data)
    {
        $this->surname = $data['surname'];
        $this->name = $data['name'];
        $this->lastname = $data['lastname'];
        $this->email = $data['email'];
        $this->password = $data['password'];
        $this->role = $data['role'];
    }
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return $this->$name;
    }
}