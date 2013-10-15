<?php
/* Версия */
Reg::version("4.2.1", true);

/* Отображение ошибок */
Reg::error_reporting(true);

/* Прописывать заголовок UTF-8 */
Reg::header_utf(false);

/* Домен */
Reg::domain($_SERVER['SERVER_NAME'], true);

/* Путь к файлам */
Reg::path_app(realpath(dirname(__FILE__) . "/.."), true);
Reg::path_www($_SERVER['DOCUMENT_ROOT'], true);
Reg::path_constr(Reg::path_app()."/constr", true);
Reg::path_admin(Reg::path_app()."/admin", true);

/* Данные для подключения по FTP, если Reg::file_manager("ftp") */
Reg::ftp_port(21);
Reg::ftp_ssl(false);

/* Данные для поключиния к PostgreSQL */
Reg::db_port("5432");
Reg::db_persistent(false);
Reg::db_ssl("disable"); // (disable | prefer | require)

/* URL страниц с ошибки */
Reg::error_404("/error/404", true);
Reg::error_403("/error/403", true);

/* Наименование путей */
Reg::url_constr("конструктор", true);	/* Для конструктора */
Reg::url_admin("админка", true);		/* Для админки */

/* Время хранения сессии в секундах */
Reg::session_time_constr(60 * 60 * 24 * 2);				/* Для конструктора */
Reg::session_time_admin(60 * 60 * 24 * 2);				/* Для админки */

/* Задержка времени в секунах перед авторизацией (защита от брутфорст) */
Reg::sleep_time_constr(1);					/* Для конструктора */

/* Пароли */
Reg::password_bcrypt_cost("05");		/* От 04 до 12. Чем больше тем сложнее и дольше получать хэш через bcrypt */
Reg::password_length_min(3);			/* Минимальное длина пароля */
Reg::password_length_max(30);		/* Максимальная длина пароля */
?>