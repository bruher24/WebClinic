<?php

class DB
{
    function createDB()
    {
        $connection = mysqli_connect('mysql', 'root', 'root');
        $connection->query('CREATE DATABASE `WebClinic`');
    }
}