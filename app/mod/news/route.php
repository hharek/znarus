<?php

/* Список */
if(Reg::url_path() === "/новости")
{
	return "list";
}

/* Содержание */
if(Reg::url_path_ar()[0] !== "новости")
{
	return false;
}

if(!isset(Reg::url_path_ar()[1]) or isset(Reg::url_path_ar()[2]))
{
	return false;
}

$query = 
<<<SQL
SELECT
	"ID"
FROM 
	"news"
WHERE 
	"Url" = $1
SQL;
$news_id = Reg::db()->query_one($query, Reg::url_path_ar()[1], "news");
if(empty($news_id))
{return false;}

Reg::news_id($news_id);

return "content";

?>