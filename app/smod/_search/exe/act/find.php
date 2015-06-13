<?php
/* Мета */
title("Поиск");
meta_title("Поиск по сайту");
meta_description("Здесь вы можете воспользоваться поиском чтобы найти необходимую информацию.");
tags("поиск по сайту, найти, поиск");
path(["Поиск [/поиск]"]);

/* Текущая страница */
$page = 1;
if(!empty($_GET['page']))
{
	$page = (int)$_GET['page'];
}

/* Слово для поиска */
$query = "";
if(!empty($_GET['query']))
{
	$query = $_GET['query'];
}

$result = [];
try
{
	/* Поиск */
	$result = _Search::find($query, $page);
	
	/* Правильный запрос */
	$query = _Search::$query;
	
	title("Поиск по слову «{$query}»");
	meta_title("Поиск по слову «{$query}»");
} 
catch (Exception $e) 
{
	$error = $e->getMessage();
}

/* Все страницы */
$page_all = 1;
if (!empty($result))
{
	$page_all = ceil((int)$result['count'] / (int)P::get("_search", "limit"));
}

?>