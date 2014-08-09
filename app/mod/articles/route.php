<?php

/* Список */
if(Reg::url_path() === "/статьи")
{
	return "list";
}

/* Содержание */
if(Reg::url_path_ar()[0] !== "статьи")
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
	"articles"
WHERE 
	"Url" = $1
SQL;
$articles_id = Reg::db()->query_one($query, Reg::url_path_ar()[1], "articles");
if(empty($articles_id))
{return false;}

Reg::articles_id($articles_id);

return "content";

?>