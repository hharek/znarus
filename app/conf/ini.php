<?php
/**
 * Настройка PHP
 */

/* Заголовок в UTF-8 */
if(HEADER_UTF8 === true)
{
	header("Content-type: text/html; charset=UTF-8");
}

/* Отображение ошибок */
if(ERROR_REPORTING === true)
{
	error_reporting(-1);
	ini_set("display_errors", 1);
}
else
{
	error_reporting(0);
	ini_set("display_errors", 0);
}

/* Кодировка для функций mbstring */
mb_internal_encoding("UTF-8");

/* Отключить заголовки сервера Cache-Control, Pragma, Expires запрещающие браузеру кэширование */
if (CACHE_ENABLE === true AND CACHE_PAGE_ENABLE === true)
{
	session_cache_limiter(false);
}

/* Старт сессии */
session_start();
?>