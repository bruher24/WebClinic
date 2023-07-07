<?php
//$connection = mysqli_connect('mysql', 'root', 'root');
//$connection->query('CREATE DATABASE `lemp-docker`');

require_once 'User/User.php';


function dataSet(): array
{
    if ($_POST) {
        $data['surname'] = $_POST['surname'] ?? null;
        $data['name'] = $_POST['name'] ?? null;
        $data['lastname'] = $_POST['lastname'] ?? null;
        $data['email'] = $_POST['email'] ?? null;
        $data['password'] = password_hash($_POST['password'] ?? null, PASSWORD_BCRYPT);
        $data['role'] = $_POST['role'] ?? null;
    }else {
        $data = null;
    }
    return $data;
}

$user =  new User(dataSet());
print_r($user);