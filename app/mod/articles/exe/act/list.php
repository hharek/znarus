<?php
$query =
<<<SQL
SELECT 
	"ID",
	"Date",
	"Title",
	"Url",
	"Anons"
FROM 
	"articles"
ORDER BY
	"Date" DESC
SQL;
$articles = Reg::db()->query_assoc($query, null, "articles");

Reg::title("Статьи");
Reg::meta_title("Статьи");
Reg::meta_keywords("статьи, полезные статьи");
Reg::path
([
	"Статьи [/статьи]"
]);
?>