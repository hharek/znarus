<?php
/* Домен */
Reg::domain("example.com", true);

/* Путь к статическим файлам */
Reg::path_www("/home/example/www", true);

/* Данные для поключиния к PostgreSQL */
Reg::db_host("127.0.0.1");
Reg::db_user("example");
Reg::db_pass("password");
Reg::db_name("example");
Reg::db_schema_core("core");
Reg::db_schema_public("public");

/* Имя и пароль для root пользователя */
Reg::root_name("root");						/* Имя root пользователя */
Reg::root_password("root");					/* Пароль для root пользователя */

/* Соль */
Reg::salt_constr("Секретная фраза");		/* Соль для сессий в кострукторе */
Reg::salt_admin("Секретная фраза");		/* Соль для хранения паролей в админку */
?>