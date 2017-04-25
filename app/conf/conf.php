<?php
/**
 * Конфигурация
 */

/* Домен */
const DOMAIN = "example.com";

/* Путь к файлам */
const DIR_APP = "/home/example/app";				/* CMS */
const DIR_PUBLIC = "/home/example/public";				/* Путь к статическим файлам */
const DIR_VAR = "/home/example/var";				/* Служебные файлы */

/* Данные для поключиния к PostgreSQL */
const DB_HOST = "127.0.0.1";
const DB_USER = "example";
const DB_PASSWORD = "db-password";
const DB_NAME = "example";
const DB_SCHEMA_CORE = "core";
const DB_SCHEMA_PUBLIC = "public";

/* Данные по администратору (root) */
const ROOT_PASSWORD = "root-password";					/* Пароль для root пользователя */
const ROOT_EMAIL = "info@" . DOMAIN;				/* Почтовый ящик для уведомлений */

/* Соль */
const SALT = "абвгдеёжзийклмнопрстуфхцчшщъыьэюя";

/* Данные по отправителю писем */
const SENDER_FROM = "sender@" . DOMAIN;			/* Адрес отправителя */
const SENDER_FROM_NAME = "EXAMPLE";				/* Имя отправителя */
?>
