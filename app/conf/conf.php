<?php
/**
 * Конфигурация
 */

/* Домен */
const DOMAIN = "example.com";

/* Путь к файлам */
const DIR_APP = "/home/example/app";				/* CMS */
const DIR_WWW = "/home/example/www";				/* Путь к статическим файлам */
const DIR_VAR = "/home/example/var";				/* Служебные файлы */

/* Данные для поключиния к PostgreSQL */
const DB_HOST = "127.0.0.1";
const DB_USER = "example";
const DB_PASSWORD = "password";
const DB_NAME = "example";
const DB_SCHEMA_CORE = "core";
const DB_SCHEMA_PUBLIC = "public";

/* Данные по администратору (root) */
const ROOT_PASSWORD = "root";					/* Пароль для root пользователя */
const ROOT_EMAIL = "info@example.com";			/* Почтовый ящик для уведомлений */

/* Соль */
const SALT = "aldsfjsakfjlkasdfjweoirsadflja123jk";

/* Данные по отправителю писем */
const SENDER_FROM = "info@" . DOMAIN;			/* Адрес отправителя */
const SENDER_FROM_NAME = "Example";				/* Имя отправителя */
?>