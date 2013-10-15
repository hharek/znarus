<?php
/*** -------------------------- Статические файлы -------------------------- ***/
$static_file = Reg::path_constr() . Reg::url_constr_path();
if (in_array(mb_substr(Reg::url(), -4), array(".css", ".jpg", ".png", ".gif")))
{
	$static_ext = mb_substr(Reg::url(), -3);
}
elseif (mb_substr(Reg::url(), -3) == ".js")
{
	$static_ext = "js";
}

if (!empty($static_ext) and is_file($static_file))
{
	/* Позволить кэшировать статические файлы */
	header("Last-Modified: ".gmdate("D, d M Y H:i:s", filemtime($static_file))." GMT");
	
	/* Заголовок Content-Type */
	switch ($static_ext)
	{
		case "css"	: header("Content-Type: text/css; charset=utf-8"); break;
		case "jpg"	: header("Content-Type: image/jpeg"); break;
		case "png"	: header("Content-Type: image/png"); break;
		case "gif"	: header("Content-Type: image/gif"); break;
		case "js"	: header("Content-Type: application/x-javascript; charset=utf-8"); break;
	}
	
	/* Заголовок Content-Length */
	header("Content-Length: " . filesize($static_file));
	
	/* Вывод */
	readfile($static_file);
	
	exit();
}

/*** --------------------------------- Основные файлы --------------------------- ***/
require Reg::path_app()."/sys/exception.php";
require Reg::path_app()."/sys/err.php";
require Reg::path_app()."/sys/chf.php";
require Reg::path_app()."/sys/file.php";
require Reg::path_app()."/sys/ftp.php";
require Reg::path_app()."/sys/pgsql.php";

require Reg::path_app()."/sys/autoloader.php";

/* Старт сессии */
session_start();
	
/*** -------------------------------- Авторизация ------------------------------- ***/
/* Процесс авторизации */
if(Reg::url_constr_path() == "/auth")
{
	require Reg::path_constr()."/auth.php";
	exit();
}

/* Выход */
if(Reg::url_constr_path() == "/exit")
{
	require Reg::path_constr()."/exit.php";
	exit();
}

try
{
	/* Проверка кук */
	if(empty($_COOKIE['sid']))
	{throw new Exception_403("Не авторизованы.", 1);}
	
	if($_COOKIE['sid'] !== md5(Reg::salt_constr() . Reg::root_name() . Reg::root_password() . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']))
	{throw new Exception_403("sid задан неверно.", 2);}
}
catch (Exception_403 $e)
{
	/* Форма с авторизацией */
	$_SESSION['constr_auth_token'] = md5(microtime(true) + mt_rand(1, 100000000));
	
	require Reg::path_constr()."/html/auth.html";
	exit();
}

/*** ------------------------------- Аякс ------------------------------- ***/
if(mb_substr(Reg::url_constr_path(), 0, 5) == "/ajax")
{
	require Reg::path_constr()."/ajax.php";
	exit();
}

/*** --------------------------- Верхнее меню --------------------------- ***/
if(mb_substr(Reg::url_constr_path(), 0, 5) == "/menu")
{
	require Reg::path_constr()."/menu.php";
	exit();
}

/*** -------------------------- Главный шаблон ----------------------------- ***/
require Reg::path_constr()."/html/index.html";
?>