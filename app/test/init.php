<?php
/*** ---------------------------- Основные классы ------------------------- ***/
require(Reg::path_app()."/sys/function.php");
require(Reg::path_app()."/sys/exception.php");
require(Reg::path_app()."/sys/err.php");
require(Reg::path_app()."/sys/chf.php");
require(Reg::path_app()."/sys/file.php");
require(Reg::path_app()."/sys/ftp.php");
require(Reg::path_app()."/sys/pgsql.php");
require(Reg::path_app()."/sys/autoloader.php");

/*** ---------------------- Класс для работы с файлами --------------------- ***/
if(Reg::file_manager() == "sys")
{
	Reg::file(new ZN_File(Reg::path_www()), true);
	Reg::file_app(new ZN_File(Reg::path_app()), true);
}
else
{
	Reg::file(new ZN_FTP(Reg::ftp_host(), Reg::ftp_user(), Reg::ftp_pass(), Reg::ftp_path_www(), Reg::ftp_port(), Reg::ftp_ssl()), true);
	Reg::file_app(clone Reg::file(), true);
	Reg::file_app()->set_path(Reg::ftp_path_app());
}

Reg::_unset("ftp_host","ftp_user","ftp_pass","ftp_path_www","ftp_path_app","ftp_port","ftp_ssl");

/*** -------------------------- Класс для работы с базой -------------------- ***/
Reg::db(new ZN_Pgsql(Reg::db_host(), Reg::db_user(), Reg::db_pass(), Reg::db_name(), Reg::db_schema_public(), Reg::db_port(), Reg::db_persistent(), Reg::db_ssl()), true);

Reg::db_core(clone Reg::db(), true);
Reg::db_core()->set_schema(Reg::db_schema_core());

Reg::_unset("db_host","db_user","db_pass","db_name","db_persistent","db_ssl");
?>