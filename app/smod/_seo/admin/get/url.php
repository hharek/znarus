<?php
$word = "";
if (!empty($_GET['word']))
{
	$word = $_GET['word'];
}

$page = 1;
if (!empty($_GET['page']))
{
	$page = (int)$_GET['page'];
}

$find = _Seo_Url::find($word, $page);
$url = $find['row'];
$count = $find['count'];

$page_all = (int) ceil($count / 20);

title("Адреса для продвижения");
path (["Адреса для продвижения"]);
?>