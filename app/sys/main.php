<?php
//header("Content-Type: text/plain");
session_start();

try
{
	/*** Конфигурация и реестр ***/
	require(dirname(__FILE__)."/reg.php");
	require(dirname(__FILE__)."/../conf/conf.php");
	require(dirname(__FILE__)."/../conf/options.php");

	/*** Основные файлы ***/
	require(Reg::path_app()."/sys/function.php");
	require(Reg::path_app()."/sys/exception.php");
	require(Reg::path_app()."/sys/error.php");
	require(Reg::path_app()."/sys/chf.php");
	require(Reg::path_app()."/sys/file.php");
	require(Reg::path_app()."/sys/ftp.php");
	require(Reg::path_app()."/sys/pgsql.php");

	/*** Класс для работы с файлами ***/
	if(Reg::file_manager() == "sys")
	{
		$file = new ZN_File(Reg::path_www());
		$file_app = new ZN_File(Reg::path_app());
	}
	else
	{
		$file = new ZN_FTP(Reg::ftp_host(), Reg::ftp_user(), Reg::ftp_pass(), Reg::ftp_path_www(), Reg::ftp_port(), Reg::ftp_ssl());
		$file_app = clone $file;
		$file_app->set_path(Reg::ftp_path_app());
	}
	Reg::file($file);
	Reg::file_app($file_app);
	unset($file, $file_app);

	Reg::_unset("ftp_host","ftp_user","ftp_pass","ftp_path_www","ftp_path_app","ftp_port","ftp_ssl");

	/*** Класс для работы с базой ***/
	$db = new ZN_Pgsql(Reg::db_host(), Reg::db_user(), Reg::db_pass(), Reg::db_name(), Reg::db_schema_public(), Reg::db_cache_dir(), Reg::db_port(), Reg::db_persistent(), Reg::db_ssl());
	
	$db_core = clone $db;
	$db_core->set_schema(Reg::db_schema_core());
	
	$db_creator = clone $db;
	$db_creator->set_schema(Reg::db_schema_creator());

	Reg::db($db, true);
	Reg::db_core($db_core, true);
	Reg::db_creator($db_creator, true);
	
	unset($db, $db_core, $db_creator);

	Reg::_unset("db_host","db_user","db_pass","db_name","db_schema_public","db_schema_core","db_cache_dir","db_persistent","db_ssl");
	
	/*** Разбор урла ***/
	Reg::url_uri(urldecode($_SERVER['REQUEST_URI']));
	Reg::url_path(parse_url(Reg::url_uri(), PHP_URL_PATH));
	
	/* Сборочная */
	if(mb_substr(Reg::url_path(), 0, mb_strlen("/".Reg::url_creator()."/", "UTF-8"), "UTF-8") == "/".Reg::url_creator()."/")
	{
		require(Reg::path_app()."/creator/index.php");
	}
	/* Конструктор */
	elseif(mb_substr(Reg::url_path(), 0, mb_strlen("/".Reg::url_constr()."/", "UTF-8"), "UTF-8") == "/".Reg::url_constr()."/")
	{
		require(Reg::path_app()."/constr/index.php");
	}
	/* Админка */
	elseif(mb_substr(Reg::url_path(), 0, mb_strlen("/".Reg::url_admin()."/", "UTF-8"), "UTF-8") == "/".Reg::url_admin()."/")
	{
		require(Reg::path_app()."/admin/index.php");
	}
	elseif(Reg::url_path() == "/test/")
	{
		require(Reg::path_app()."/test/test.php");
	}
	/* Основной вывод */
	else
	{
		require(Reg::path_app()."/sys/index.php");
	}
	
	
}
/* 404 ошибка */
catch (Exception_404 $e)
{
	header("HTTP/1.0 404 Not Found");
	exit();
}
catch (Exception $e)
{
	echo $e->getMessage();
//	echo $e->__toString();
	
}


?>
