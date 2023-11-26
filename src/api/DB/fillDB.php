<?php

$mysqli = new mysqli('mysql', 'root', 'root', 'WebClinic');

$mysqli->query("
    INSERT INTO `users` (`user_id`, `surname`, `name`, `firstname`, `email`, `passwordHash`, `phpsessid`) VALUES
        (1, 'Тестов', 'Тест', 'Тестович', 'test1@test.com', '$2y$10$zdyeT4KiHOz0b1mTJHV24e5ya2dGAm7GJSm2Av5r90sHeN4pGa5n6', NULL),
        (6, 'Глушков', 'Дмитрий', 'Дмитриевич', 'email@email.com', '$2y$10$hqy4nCJDchnGzH3SFLfDwOYy/AS6nYCeDTe9zRavNCwhJEWSGULLC', NULL),
        (7, 'Глушков', 'Дмитрий', 'Дмитриевич', 'asdemail@email.com', '$2y$10$tEGAUaS2gzQj9m/g0xAZxO5jmT9iEjnA8nKCtBy6dQmr5Y6ofgd7m', NULL);
    
    INSERT INTO `doctors` (`doc_id`, `surname`, `name`, `firstname`, `email`, `passwordHash`, `speciality`, `price`, `phpsessid`) VALUES
        (1, 'Докторов', 'Доктор', 'Докторович', 'doc1@doc.com', '$2y$10$WEf3.Lsbn.JKwEsuzT7os.3Czf0yOdFdNtWhUILYbtRBH6YeUyeZi', 'Терапевт', 300, 'fcc8ced87469094611a5e3f1e10597ce'),
        (2, 'Докторов', 'Доктор', 'Докторович', 'doc2@doc.com', '$2y$10$0JJO4GKuGjEqwFbNSRDET.7aUFAhA9uE0z.SySkkxJ/rhMwo7CMm.', 'Терапевт', 300, '26f3a1d6a15d256514e089a5db5b29d9'),
        (3, 'Докторов', 'Доктор', 'Докторович', 'doc3@doc.com', '$2y$10$ux4/rBOiU/Zh/IXqDdtKyuycen2PsXOnOabok1ckLFynq2D4lyuRK', 'Терапевт', NULL, NULL);
    
    INSERT INTO `schedule` (`datetime_id`, `date`, `time`, `doc_id`, `doc_name`, `speciality`, `user_id`, `user_name`, `price`) VALUES
        (3, '2023-11-30', '08:30:00', 2, 'Докторов Доктор Докторович', 'Терапевт', 1, 'Тестов Тест Тестович', 300),
        (4, '2030-11-20', '09:30:00', 2, 'Докторов Доктор Докторович', 'Терапевт', 1, 'Тестов Тест Тестович', 300),
        (5, '2023-11-30', '10:30:00', 2, 'Докторов Доктор Докторович', 'Терапевт', 1, 'Тестов Тест Тестович', 300);
    
    INSERT INTO `specialities` (`speciality`, `tools`, `base_tools`) VALUES
        ('Терапевт', 'Шпатель', 'Маска, Перчатки');
    
    INSERT INTO `timetable` (`time_id`, `time`) VALUES
        (1, '08:00:00'),
        (2, '08:30:00'),
        (3, '09:00:00'),
        (4, '09:30:00'),
        (5, '10:00:00'),
        (6, '10:30:00'),
        (7, '11:00:00'),
        (8, '11:30:00'),
        (9, '12:00:00'),
        (10, '12:30:00'),
        (11, '13:00:00'),
        (12, '13:30:00'),
        (13, '14:00:00'),
        (14, '14:30:00'),
        (15, '15:00:00'),
        (16, '15:30:00'),
        (17, '16:00:00'),
        (18, '16:30:00');
    
    INSERT INTO `tools` (`tool_name`, `using_time`) VALUES
        ('Перчатки', '00:30:00'),
        ('Маска', '02:00:00'),
        ('Шпатель', '00:30:00');
    
    INSERT INTO `tools_usage` (`doc_id`, `doc_name`, `speciality`, `tool_name`, `used_amount`, `date`) VALUES
        (2, 'Докторов Доктор Докторович', 'Терапевт', 'Шпатель', 3, '2023-11-25'),
        (2, 'Докторов Доктор Докторович', 'Терапевт', 'Маска', 1, '2023-11-25'),
        (2, 'Докторов Доктор Докторович', 'Терапевт', 'Перчатки', 3, '2023-11-25');

    ALTER TABLE `doctors`
        ADD PRIMARY KEY (`doc_id`);

    ALTER TABLE `schedule`
        ADD PRIMARY KEY (`datetime_id`),
        ADD KEY `doc_id` (`doc_id`),
        ADD KEY `user_id` (`user_id`),
        ADD KEY `time` (`time`),
        ADD KEY `speciality` (`speciality`);

    ALTER TABLE `specialities`
        ADD PRIMARY KEY (`speciality`);

    ALTER TABLE `timetable`
        ADD PRIMARY KEY (`time`),
        ADD KEY `time_id` (`time_id`);

    ALTER TABLE `tools`
        ADD KEY `tool_name` (`tool_name`);

    ALTER TABLE `tools_usage`
        ADD KEY `doc_id` (`doc_id`);

    ALTER TABLE `users`
        ADD PRIMARY KEY (`user_id`);

    ALTER TABLE `doctors`
        MODIFY `doc_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

    ALTER TABLE `schedule`
        MODIFY `datetime_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

    ALTER TABLE `timetable`
        MODIFY `time_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

    ALTER TABLE `users`
        MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

    ALTER TABLE `schedule`
        ADD CONSTRAINT `schedule_ibfk_1` FOREIGN KEY (`doc_id`) REFERENCES `doctors` (`doc_id`),
        ADD CONSTRAINT `schedule_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
        ADD CONSTRAINT `schedule_ibfk_3` FOREIGN KEY (`time`) REFERENCES `timetable` (`time`),
        ADD CONSTRAINT `schedule_ibfk_4` FOREIGN KEY (`speciality`) REFERENCES `specialities` (`speciality`);

    ALTER TABLE `tools_usage`
        ADD CONSTRAINT `tools_usage_ibfk_1` FOREIGN KEY (`doc_id`) REFERENCES `doctors` (`doc_id`);
    COMMIT;
");

$mysqli->close();