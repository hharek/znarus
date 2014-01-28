<?php
/*** -------------------------- Статические файлы -------------------------- ***/
$static_file = Reg::path_admin() . Reg::url_admin_path();
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

/*** ---------------------------- Основные файлы --------------------------- ***/
require Reg::path_app()."/sys/exception.php";
require Reg::path_app()."/sys/err.php";
require Reg::path_app()."/sys/chf.php";
require Reg::path_app()."/sys/file.php";
require Reg::path_app()."/sys/ftp.php";
require Reg::path_app()."/sys/pgsql.php";

require Reg::path_app()."/sys/autoloader.php";

/* Старт сессии */
session_start();

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

//Reg::_unset("ftp_host","ftp_user","ftp_pass","ftp_path_www","ftp_path_app","ftp_port","ftp_ssl");

/*** -------------------------- Класс для работы с базой -------------------- ***/
Reg::db(new ZN_Pgsql(Reg::db_host(), Reg::db_user(), Reg::db_pass(), Reg::db_name(), Reg::db_schema_public(), Reg::db_port(), Reg::db_persistent(), Reg::db_ssl()), true);

Reg::db_core(clone Reg::db(), true);
Reg::db_core()->set_schema(Reg::db_schema_core());

//Reg::_unset("db_host","db_user","db_pass","db_name","db_persistent","db_ssl");

/*** ------------------------------ Авторизация ----------------------------- ***/
/* Процесс авторизации */
if(Reg::url_admin_path() == "/auth")
{
	require Reg::path_admin()."/auth.php";
	exit();
}

/* Выход */
if(Reg::url_admin_path() == "/exit")
{
	require Reg::path_admin()."/exit.php";
	exit();
}

try
{
	/* Проверка кук */
	if(empty($_COOKIE['sid']))
	{throw new Exception_403("Не авторизованы.", 1);}
	
	if(!ZN_User_Action::session_check())
	{throw new Exception_403("sid задан неверно.", 2);}
}
catch (Exception_403 $e)
{
	/* Форма с авторизацией */
	$_SESSION['admin_auth_token'] = md5(microtime(true) + mt_rand(1, 100000000));
	
	require Reg::path_admin()."/html/auth.html";
	exit();
}

/*** ------------------------------- Аякс ------------------------------- ***/
if(mb_substr(Reg::url_admin_path(), 0, 5) == "/ajax")
{
	require Reg::path_admin()."/ajax.php";
	exit();
}

/*** --------------------------- Верхнее меню --------------------------- ***/
if(mb_substr(Reg::url_admin_path(), 0, 5) == "/menu")
{
	require Reg::path_admin()."/menu.php";
	exit();
}

/*** -------------------------Страница не найдена ----------------------- ***/
if(Reg::url_admin_path() !== "/")
{
	header("Content-type: text/plain");
	echo "Страница не найдена";
	exit();
}


/*** -------------------------------- Меню ------------------------------ ***/
$user = ZN_User_Action::data();

/* Если не root */
if($user['Email'] !== "root")
{
	$query =
<<<SQL
SELECT 
	"a"."ID", 
	"a"."Name", 
	"a"."Identified",
	"a"."Module_ID"
FROM 
	"user_priv" as "up", 
	"admin" as "a"
WHERE 
	"up"."Group_ID" = $1 AND 
	"up"."Admin_ID" = "a"."ID" AND 
	"a"."Visible" = true
ORDER BY 
	"a"."Sort" ASC
SQL;
	$admin = Reg::db_core()->query_assoc($query, $user['Group_ID'], array("user_priv","admin"));

	$query =
<<<SQL
SELECT DISTINCT
	"m"."ID",
	"m"."Name",
	"m"."Identified"
FROM 
	"user_priv" as "up", 
	"admin" as "a",
	"module" as "m"
WHERE 
	"up"."Group_ID" = $1 AND 
	"up"."Admin_ID" = "a"."ID" AND 
	"a"."Visible" = true AND
	"a"."Module_ID" = "m"."ID"
ORDER BY
	"m"."Identified" ASC
SQL;
	$module = Reg::db_core()->query_assoc($query, $user['Group_ID'], array("user_priv","admin","module"));
}
/* root */
else
{
	$query =
<<<SQL
SELECT
	"ID", 
	"Name", 
	"Identified",
	"Module_ID"
FROM 
	"admin"
WHERE 
	"Visible" = true
ORDER BY 
	"Sort" ASC
SQL;
	$admin = Reg::db_core()->query_assoc($query, null, "admin");
	
	$query =
<<<SQL
SELECT DISTINCT
	"m"."ID",
	"m"."Name",
	"m"."Identified"
FROM 
	"admin" as "a",
	"module" as "m"
WHERE 
	"a"."Visible" = true AND
	"a"."Module_ID" = "m"."ID"
ORDER BY
	"m"."Identified" ASC
SQL;
	$module = Reg::db_core()->query_assoc($query, null, ["admin","module"]);
}

foreach ($module as $m_key=>$m_val)
{
	$module[$m_key]['admin'] = array();
	foreach ($admin as $a_key=>$a_val)
	{
		if($m_val['ID'] === $a_val['Module_ID'])
		{
			$module[$m_key]['admin'][] = $a_val;
			unset($admin[$a_key]);
		}
	}
}


/*** -------------------------- Основной шаблон -------------------------- ***/
require Reg::path_admin() . "/html/index.html";
?>