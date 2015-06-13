<?php
/* Год */
$current_year = date("Y");
if (isset($_GET['year']))
{
	$current_year = (int) $_GET['year'];
}

/* Статьи */
$query = 
<<<SQL
SELECT 
	"ID", 
	"Date", 
	"Title", 
	"Url", 
	"Anons", 
	"Content"
FROM 
	"articles"
WHERE
	EXTRACT(YEAR FROM "Date") = $1
ORDER BY
	"Date" DESC 
SQL;
$articles = G::db()->query($query, $current_year)->assoc();

/* Года */
$query = 
<<<SQL
SELECT DISTINCT EXTRACT(YEAR FROM "Date") as "date"
FROM 
	"articles"
ORDER BY
	"date" ASC
SQL;
$year = G::db()->query($query)->column();
if (!in_array(date("Y"), $year))
{
	$year[] = date("Y");
}
asort($year);

/* Заголовок */
title("Список статьей за {$current_year} год");
path(["Статьи за {$current_year} год"]);
?>