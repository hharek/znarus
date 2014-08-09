<?php
/* Версия */
Reg::cms_version("0.6b", true);

/* Отображение ошибок */
Reg::error_reporting(true);

/* Прописывать заголовок UTF-8 */
Reg::header_utf(false);

/* Путь к файлам */
Reg::path_app(realpath(dirname(__FILE__) . "/.."), true);
Reg::path_constr(Reg::path_app()."/constr", true);
Reg::path_admin(Reg::path_app()."/admin", true);

/* Режим работы с файлами (sys|ftp) */
Reg::file_manager("sys", true);

/* Данные для подключения по FTP, если Reg::file_manager("ftp") */
Reg::ftp_host("");
Reg::ftp_user("");
Reg::ftp_pass("");
Reg::ftp_path_app("");
Reg::ftp_path_www("");
Reg::ftp_port(21);
Reg::ftp_ssl(false);

/* Данные для поключиния к PostgreSQL */
Reg::db_port("5432");
Reg::db_persistent(false);
Reg::db_ssl("disable"); // (disable | prefer | require)

/* Кэширование для PostgreSQL */
Reg::db_cache(false);						/* Использовать ли кэширование */
Reg::db_cache_salt("");						/* Соль для кэша */
Reg::db_cache_type("file");					/* Тип кэширования (file|memcache) */

/* Тип кэширования Reg::db_cache_type("file") */
Reg::db_cache_dir("");	

/* Тип кэширования Reg::db_cache_type("memcache") */
Reg::db_cache_memcache_host("");
Reg::db_cache_memcache_port("");

/* Наименование путей */
Reg::url_constr("конструктор", true);	/* Для конструктора */
Reg::url_admin("админка", true);		/* Для админки */
Reg::url_end("");						/* Окончание урла */

/* Время хранения сессии в секундах */
Reg::session_time_constr(60 * 60 * 24 * 2);				/* Для конструктора */
Reg::session_time_admin(60 * 60 * 24 * 2);				/* Для админки */

/* Задержка времени в секунах перед авторизацией (защита от брутфорст) */
Reg::sleep_time_constr(1);					/* Для конструктора */
Reg::sleep_time_admin(1);					/* Для админки */

/* Пароли */
Reg::password_bcrypt_cost("05");		/* От 04 до 12. Чем больше тем сложнее и дольше получать хэш через bcrypt */
Reg::password_length_min(3);			/* Минимальное длина пароля */
Reg::password_length_max(30);		/* Максимальная длина пароля */
?>