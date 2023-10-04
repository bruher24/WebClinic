<?php

$mysqli = new mysqli('mysql', 'root', 'root');

if (!$mysqli->query("CREATE DATABASE IF NOT EXISTS `WebClinic`")) echo $mysqli->error;
$mysqli->close();

$mysqli = new mysqli('mysql', 'root', 'root', 'WebClinic');

if ($mysqli->query("CREATE TABLE IF NOT EXISTS `users` (
            `user_id` int NOT NULL AUTO_INCREMENT,
            `surname` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
            `name` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
            `firstname` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
            `email` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
            `passwordHash` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
            `role` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
            `speciality` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
            `price` int DEFAULT NULL,
            `phpsessid` varchar(128) COLLATE utf8mb4_general_ci DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;")) $mysqli->query("ALTER TABLE `users` ADD PRIMARY KEY (`user_id`);");

else echo $mysqli->error;


if ($mysqli->query("CREATE TABLE IF NOT EXISTS `schedule` (
  `date_id` int NOT NULL AUTO_INCREMENT,
  `datetime` datetime DEFAULT NULL,
  `doc_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `price` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;")) $mysqli->query("ALTER TABLE `schedule` ADD PRIMARY KEY (`date_id`);");

else echo $mysqli->error;
$mysqli->close();


