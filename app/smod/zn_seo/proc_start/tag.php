<?php
/* Первоначальные данные */
Reg::meta_title("");
Reg::meta_keywords("");
Reg::meta_description("");

/* Является ли адресом для продвижения */
$query = 
<<<SQL
SELECT
	"Title",
	"Keywords",
	"Description"
FROM 
	"seo_url"
WHERE
	"Url" = $1
SQL;
$seo_url = Reg::db_core()->query_line($query, Reg::url_path(), "seo_url");
if(!empty($seo_url))
{
	if(!empty($seo_url['Title']))
	{Reg::meta_title($seo_url['Title']);}
	
	if(!empty($seo_url['Keywords']))
	{Reg::meta_keywords($seo_url['Keywords']);}
	
	if(!empty($seo_url['Description']))
	{Reg::meta_description($seo_url['Description']);}
}
?>