<?php

$mysqli = new mysqli('mysql', 'root', 'root');

if (!$mysqli->query("CREATE DATABASE IF NOT EXISTS `WebClinic`")) echo $mysqli->error;
$mysqli->close();

$mysqli = new mysqli('mysql', 'root', 'root', 'WebClinic');

if (!$mysqli->query("CREATE TABLE IF NOT EXISTS `users` (
            `user_id` int NOT NULL AUTO_INCREMENT,
            `surname` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
            `name` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
            `firstname` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
            `email` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
            `passwordHash` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
            `phpsessid` varchar(128) COLLATE utf8mb4_general_ci DEFAULT NULL,
            PRIMARY KEY (`user_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;")) echo $mysqli->error;


if (!$mysqli->query("CREATE TABLE IF NOT EXISTS `doctors` (
            `doc_id` int NOT NULL AUTO_INCREMENT,
            `surname` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
            `name` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
            `firstname` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
            `email` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
            `passwordHash` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
            `speciality` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
            `price` int DEFAULT NULL,
            `phpsessid` varchar(128) COLLATE utf8mb4_general_ci DEFAULT NULL,
            PRIMARY KEY (`doc_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;")) echo $mysqli->error;


if (!$mysqli->query("CREATE TABLE IF NOT EXISTS `schedule` (
            `date_id` int NOT NULL AUTO_INCREMENT,
            `datetime` datetime DEFAULT NULL,
            `doc_id` int DEFAULT NULL,
            `doc_name` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
            `user_id` int DEFAULT NULL,
            `user_name` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
            `price` int NOT NULL,
            PRIMARY KEY (`date_id`),
            FOREIGN KEY (`doc_id`) REFERENCES `doctors`(`doc_id`),
            FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;")) echo $mysqli->error;



