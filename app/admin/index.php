<?php
/*** -------------------------- Статические файлы -------------------------- ***/
switch ("/" . implode("/", array_slice(Reg::url_path_ar(), 1)))
{
	case "/css/default.css":
	{
		header("Content-Type: text/css");
		header("Content-Length: ". Reg::path_admin() . "/css/default.css");
		readfile(Reg::path_admin() . "/css/default.css");
		exit();
	}
	break;

	case "/css/icon.css":
	{
		header("Content-Type: text/css");
		header("Content-Length: ". Reg::path_admin() . "/css/icon.css");
		readfile(Reg::path_admin() . "/css/icon.css");
		exit();
	}
	break;

	case "/img/logo.png":
	{
		header("Content-Type: image/png");
		header("Content-Length: ". Reg::path_admin() . "/img/logo.png");
		readfile(Reg::path_admin() . "/img/logo.png");
		exit();
	}
	break;

	case "/img/icon.png":
	{
		header("Content-Type: image/png");
		header("Content-Length: ". Reg::path_admin() . "/img/icon.png");
		readfile(Reg::path_admin() . "/img/icon.png");
		exit();
	}
	break;

	case "/js/jquery-1.9.1.min.js":
	{
		header("Content-Type: application/x-javascript");
		header("Content-Length: ". Reg::path_admin() . "/js/jquery-1.9.1.min.js");
		readfile(Reg::path_admin() . "/js/jquery-1.9.1.min.js");
		exit();
	}
	break;

	case "/js/default.js":
	{
		header("Content-Type: application/x-javascript");
		header("Content-Length: ". Reg::path_admin() . "/js/default.js");
		readfile(Reg::path_admin() . "/js/default.js");
		exit();
	}
	break;
}

/*** -------------------------- Основной шаблон -------------------------- ***/
require Reg::path_admin() . "/html/index.html";

?>