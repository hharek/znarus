<?php

/* Версия */
Reg::version("4.0.1", true);

/* Домен */
Reg::domain($_SERVER['SERVER_NAME'], true);

/* Урлы ошибок */
Reg::path_app(realpath(dirname(__FILE__)."/.."), true);
Reg::path_www($_SERVER['DOCUMENT_ROOT'], true);
Reg::path_tmp(sys_get_temp_dir(), true);

/* Тип файлового менеджера (sys|ftp) */
Reg::file_manager("sys", true);

/* Данные для подключения по FTP */
Reg::ftp_port(21);
Reg::ftp_ssl(false);

/* Данные для поключиния к PostgreSQL */
Reg::db_port("5432");
Reg::db_persistent(false);
Reg::db_ssl("disable"); // (disable | prefer | require)

/* Ошибки */
Reg::error_404("/error/404", true);
Reg::error_403("/error/403", true);

/* Наименование путей для конструктора и админки */
Reg::url_creator("creator", true);
Reg::url_constr("constr", true);
Reg::url_admin("admin", true);

?>