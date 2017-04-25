<?php
/* Настройки */
$_static_ext_allow = ["css", "jpg", "png", "gif", "js", "woff", "ttf", "html"];		/* Разрешённые расширения для статических файлов */
$_shared_css = ["/index.css", "/auth.css", "/restore.css"];			/* Общие файлы CSS */
$_shared_js =  ["/index.js",  "/auth.js",  "/restore.js"];			/* Общие файлы JS */

/* Расширение */
$_static_ext = "";
$_static_explode = explode(".", G::url_path());
if ($_static_explode > 1)
{
	$_static_ext = end($_static_explode);
}

/* PHP-файлы */
if ($_static_ext === "php")
{
	require DIR_TOOLS . "/_packjs/" . substr(G::url_path(), 8);
	exit();
}

/* Статические файлы */
if (in_array($_static_ext, $_static_ext_allow))
{
	if (strpos(G::url_path(), "..") !== false)														/* Недопустимый символ в файле */
	{
		header("HTTP/1.0 404 Not Found");
		throw new Exception("404. Страница не найдена.");
	}
	
	/* Общий файл CSS  */
	if (in_array(G::url_path(), $_shared_css))
	{
		require DIR_TOOLS . "/_shared/php/css.php";
	}
	/* Общий файл JS */
	elseif (in_array(G::url_path(), $_shared_js))
	{
		require DIR_TOOLS . "/_shared/php/js.php";
	}
	/* Иконки к модулям */
	elseif (G::url_path_ar()[0] === "mod_icon" and isset($_SESSION['_tools_session_check']))
	{
		$_mod = explode(".", G::url_path_ar()[1])[0];
		
		if (substr($_mod, 0, 1) === "_")
		{
			$_icon = DIR_APP . "/smod/{$_mod}/icon.png";
		}
		else
		{
			$_icon = DIR_APP . "/mod/{$_mod}/icon.png";
		}
		
		if (!is_file($_icon))
		{
			$_icon = DIR_TOOLS . "/_shared/img/mod.png";
		}

		header("Content-Type: image/png");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s", filemtime($_icon)) . " GMT");
		header("Content-Length: " . filesize($_icon));
		readfile($_icon);
		exit();
	}
	/* Статические файлы */
	else
	{
		$_static_file = DIR_TOOLS . "/_shared" . G::url_path();										/* Путь к статическому файлу */
		if (G::url_path_ar()[0] === "packjs" and isset($_SESSION['_tools_session_check']))
		{
			$_static_file = DIR_TOOLS . "/_packjs/" . substr(G::url_path(), 8);
		}
		
		if (!is_file($_static_file))																/* Файл не найден */
		{
			header("HTTP/1.0 404 Not Found");
			throw new Exception("404. Страница не найдена.");
		}
		
		header("Last-Modified: " . gmdate("D, d M Y H:i:s", filemtime($_static_file)) . " GMT");	/* Позволить кэшировать статические файлы */

		switch ($_static_ext)																		/* Заголовок */
		{
			case "css"	: header("Content-Type: text/css; charset=utf-8"); break;
			case "jpg"	: header("Content-Type: image/jpeg"); break;
			case "png"	: header("Content-Type: image/png"); break;
			case "gif"	: header("Content-Type: image/gif"); break;
			case "js"	: header("Content-Type: application/x-javascript; charset=utf-8"); break;
		}

		header("Content-Length: " . filesize($_static_file));										/* Заголовок Content-Length */

		readfile($_static_file);																	/* Вывод */
		exit();
	}
}
?>