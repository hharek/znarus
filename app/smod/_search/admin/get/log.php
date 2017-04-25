<?php
title("Запросы");
path(["Запросы"]);

/* Слово */
$word = "";
if (!empty($_GET['word']))
{
	$word = $_GET['word'];
}
$word = _Search::delete_bad_symbol($word);

/* Всего страниц */
$query = 
<<<SQL
SELECT
	COUNT(*)
FROM
	"search_log"
WHERE
	"Query" LIKE $1
SQL;
$count = G::db_core()->query($query, "%{$word}%")->single();
$limit = 30;
$page_all = (int)ceil($count / $limit);

/* Текущая страница */
$page = 1;
if (!empty($_GET['page']))
{
	$page = (int)$_GET['page'];
}

$offset = ($page - 1) * $limit;

/* Запрос */
$query = 
<<<SQL
SELECT
	"ID",
	"Query",
	"Date",
	"IP"
FROM
	"search_log"
WHERE
	"Query" LIKE $1
ORDER BY
	"Date" DESC 
OFFSET {$offset}
LIMIT {$limit}
SQL;
$log = G::db_core()->query($query, "%{$word}%")->assoc();



?>