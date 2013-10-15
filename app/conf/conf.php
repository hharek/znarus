<?php
/* Режим работы с файлами (sys|ftp) */
Reg::file_manager("sys", true);

/* Данные для подключения по FTP, если Reg::file_manager("ftp") */
Reg::ftp_host("localhost");
Reg::ftp_user("ftp_user");
Reg::ftp_pass("ftp_password");
Reg::ftp_path_app("/app");
Reg::ftp_path_www("/www");

/* Данные для поключиния к PostgreSQL */
Reg::db_host("127.0.0.1");
Reg::db_user("db_user");
Reg::db_pass("db_password");
Reg::db_name("znarus");
Reg::db_schema_core("core");
Reg::db_schema_public("public");

/* Кэширование для PostgreSQL */
Reg::db_cache(true);						/* Использовать ли кэширование */
Reg::db_cache_salt("Секретная фраза");		/* Соль для кэша */
Reg::db_cache_type("memcache");					/* Тип кэширования (file|memcache) */

/* Если тип кэширования Reg::db_cache_type("file") */
Reg::db_cache_dir("");	

/* Если тип кэширования Reg::db_cache_type("memcache") */
Reg::db_cache_memcache_host("localhost");
Reg::db_cache_memcache_port("11211");

/* Имя и пароль для root пользователя */
Reg::root_name("root");						/* Имя root пользователя */
Reg::root_password("password");					/* Пароль для root пользователя */

/* Соль */
Reg::salt_constr("Секретная фраза");		/* Соль для сессий в кострукторе */
Reg::salt_admin("Секретная фраза");		/* Соль для хранения паролей в админку */


?>