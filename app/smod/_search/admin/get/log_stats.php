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

/* Сортировка */
$order = "count";
if (!empty($_GET['order']) and $_GET['order'] === "query")
{
	$order = "query";
}

if ($order === "count")
{
	$sql_order = 
<<<SQL
ORDER BY
	"Count" DESC,
	"Query" ASC
SQL;
}
else if ($order === "query")
{
	$sql_order = 
<<<SQL
ORDER BY
	"Query" ASC
SQL;
}

/* Всего страниц */
$query = 
<<<SQL
SELECT 
	COUNT(*)
FROM 
(
	SELECT
		"Query",
		COUNT(*) as "Count"
	FROM
		"search_log"
	WHERE
		"Query" LIKE $1
	GROUP BY
		"Query"
) as t
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
	"Query",
	COUNT(*) as "Count"
FROM
	"search_log"
WHERE
	"Query" LIKE $1
GROUP BY
	"Query"
{$sql_order}
OFFSET {$offset}
LIMIT {$limit}
SQL;
$log = G::db_core()->query($query, "%{$word}%")->assoc();
?>