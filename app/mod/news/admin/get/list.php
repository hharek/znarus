<?php
/* Год */
$current_year = date("Y");
if (isset($_GET['year']))
{
	$current_year = (int) $_GET['year'];
}

/* Новости */
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
	"news"
WHERE
	EXTRACT(YEAR FROM "Date") = $1
ORDER BY
	"Date" DESC 
SQL;
$news = G::db()->query($query, $current_year)->assoc();

/* Года */
$query = 
<<<SQL
SELECT DISTINCT EXTRACT(YEAR FROM "Date") as "date"
FROM 
	"news"
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
title("Список новостей за {$current_year} год");
path(["Новости за {$current_year} год"]);
?>