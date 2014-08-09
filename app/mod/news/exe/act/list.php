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
	"news"
ORDER BY
	"Date" DESC
SQL;
$news = Reg::db()->query_assoc($query, null, "news");

Reg::title("Новости");
Reg::meta_title("Новости");
Reg::meta_keywords("новости, новости сайта, последние новости");
Reg::path
([
	"Новости [/новости]"
]);
?>