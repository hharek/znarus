<?php
/* Конфигурация и реестр */
require dirname(__FILE__)."/sys/reg.php";
require dirname(__FILE__)."/conf/conf.php";
require dirname(__FILE__)."/conf/options.php";



/* Общее */
if(Reg::error_reporting())
{error_reporting(-1);}
else
{error_reporting(0);}

mb_internal_encoding("UTF-8");

/* Разбор урла */
Reg::url(urldecode($_SERVER['REQUEST_URI']));
Reg::url_path(parse_url(Reg::url(), PHP_URL_PATH));
Reg::url_path_ar(explode("/", mb_substr(Reg::url_path(), 1)));

/* Не слэша на конце */
if(in_array(Reg::url(), array("/" . Reg::url_constr(), "/" . Reg::url_admin())))
{
	header("Location: " . Reg::url() . "/");
}

/* Конструктор */
if (mb_substr(Reg::url(), 0, mb_strlen(Reg::url_constr()) + 2) === "/" . Reg::url_constr(). "/")
{
	Reg::url_constr_path("/" . implode("/", array_slice(Reg::url_path_ar(), 1)));
	Reg::location("constr");
	require Reg::path_app()."/constr/index.php";
}
/* Админка */
elseif (mb_substr(Reg::url(), 0, mb_strlen(Reg::url_admin()) + 2) === "/" . Reg::url_admin(). "/")
{
	Reg::url_admin_path("/" . implode("/", array_slice(Reg::url_path_ar(), 1)));
	Reg::location("admin");
	require Reg::path_app()."/admin/index.php";
}
/* Тестирование */
elseif (Reg::url_path() == "/test")
{
	Reg::location("test");
	require Reg::path_app()."/test/index.php";
}
/* Основной вывод */
else
{
	Reg::location("front");
	require Reg::path_app()."/front.php";
}
?>