<?php
/* Конфигурация  */
require dirname(__FILE__)."/conf/conf.php";
require dirname(__FILE__)."/conf/options.php";
require dirname(__FILE__)."/conf/ini.php";

/* Глобальные переменные */
require dirname(__FILE__)."/sys/g.php";

/* Разбор урла */
G::url(urldecode($_SERVER['REQUEST_URI']));
G::url_path(parse_url(G::url(), PHP_URL_PATH));
if (G::url_path() !== "/")
{
	G::url_path_ar(explode("/", mb_substr(G::url_path(), 1)));
}
else
{
	G::url_path_ar([]);
}

/* Инструменты (конструктор или админка) */
if 
(
	(
		isset(G::url_path_ar()[0]) and
		G::url_path_ar()[0] === URL_CONSTR and 
		is_dir(DIR_TOOLS . "/constr") and
		CONSTR_ENABLE === true
	) 
	or
	(
		isset(G::url_path_ar()[0]) and
		G::url_path_ar()[0] === URL_ADMIN and 
		is_dir(DIR_TOOLS . "/admin") and
		ADMIN_ENABLE === true
	)
)
{
	require DIR_APP."/tools/index.php";
}
/* Тест */
elseif (G::url_path() === "/" . URL_TEST)
{
	G::location("test");
	require DIR_APP."/test/index.php";
}
/* Аякс */
elseif 
(
	isset(G::url_path_ar()[0]) and
	G::url_path_ar()[0] === URL_AJAX
)
{
	G::location("ajax");
	require DIR_APP."/ajax/index.php";
}
/* Основной вывод */
else
{
	G::location("front");
	require DIR_APP."/front/index.php";
}
?>