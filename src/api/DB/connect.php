<?php

$mysqli = new mysqli('mysql', 'root', 'root');

if (!$mysqli->query("CREATE DATABASE IF NOT EXISTS `WebClinic`")) echo $mysqli->error;
$mysqli->close();

$mysqli = new mysqli('mysql', 'root', 'root', 'WebClinic');

if (!$mysqli->query("CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL,
  `surname` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `firstname` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `passwordHash` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phpsessid` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;")) echo $mysqli->error;





if (!$mysqli->query("CREATE TABLE IF NOT EXISTS `doctors` (
  `doc_id` int NOT NULL,
  `surname` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `firstname` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `passwordHash` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `speciality` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` int DEFAULT NULL,
  `lunch_time` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phpsessid` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;")) echo $mysqli->error;





if (!$mysqli->query("CREATE TABLE IF NOT EXISTS `schedule` (
  `datetime_id` int NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `doc_id` int DEFAULT NULL,
  `doc_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `speciality` varchar(128) NOT NULL,
  `user_id` int DEFAULT NULL,
  `user_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;")) echo $mysqli->error;





if (!$mysqli->query("CREATE TABLE IF NOT EXISTS `specialities` (
  `speciality` varchar(128) NOT NULL,
  `tools` varchar(128) DEFAULT NULL,
  `base_tools` varchar(128) NOT NULL DEFAULT 'Маска, Перчатки'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;")) echo $mysqli->error;





if (!$mysqli->query("CREATE TABLE IF NOT EXISTS `timetable` (
  `time_id` int NOT NULL,
  `time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;")) echo $mysqli->error;





if (!$mysqli->query("CREATE TABLE IF NOT EXISTS `tools` (
  `tool_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `using_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;")) echo $mysqli->error;





if (!$mysqli->query("CREATE TABLE IF NOT EXISTS `tools_usage` (
  `doc_id` int NOT NULL,
  `doc_name` varchar(128) NOT NULL,
  `speciality` varchar(128) NOT NULL,
  `tool_name` varchar(128) NOT NULL,
  `used_amount` int NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;")) echo $mysqli->error;

$mysqli->close();
