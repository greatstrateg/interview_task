<?php
/**
Имеется таблица логов пользователей (логин пользователя, id записи, дата-время, событие).
Напишите sql-запрос, который выводит логин и последнее событие каждого пользовател
 **/

##Создание таблицы
//CREATE TABLE `user_log` (
//`id_log` int(11) NOT NULL,
//  `user_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
//  `date_time` datetime NOT NULL,
//  `event` varchar(255) COLLATE utf8_unicode_ci NOT NULL
//) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
##Установка первичного ключа
//ALTER TABLE `user_log`
//  ADD PRIMARY KEY (`id_log`);

//Решение задачи через LEFT JOIN
$zap  = "SELECT A.name, A.event FROM user_log A LEFT JOIN (SELECT user_name, MAX(date_time) AS date_time FROM user_log GROUP BY user_name) B ON A.user_name=B.user_name AND A.date_time=B.date_time WHERE B.user_name IS NOT NULL";

//Решение задачи через RIGHT JOIN
$zap2 = "SELECT A.name, A.event FROM user_log A RIGHT JOIN (SELECT user_name, MAX(date_time) AS date_time FROM user_log GROUP BY user_name) B ON A.user_name=B.user_name AND A.date_time=B.date_time";
