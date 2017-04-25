<?php
/* Источник */
$current_from = "";
if (!empty($_GET['from']))
{
	$current_from = $_GET['from'];
}

/* Страница */
$current_page = 1;
if (!empty($_GET['page']))
{
	$current_page = (int)$_GET['page'];
}

/* Выборка */
$redirect = _Seo_Redirect::search($current_from, $current_page);

/* Всего страниц */
$page = ceil($redirect['count'] / 20);

/* Заголовок */
title("Переадресация");
path(["Переадресация"]);
?>