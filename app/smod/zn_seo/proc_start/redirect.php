<?php
/* Переадресации */
$query = 
<<<SQL
SELECT
	"To"
FROM 
	"seo_redirect"
WHERE
	"From" = $1
SQL;
$to = Reg::db_core()->query_one($query, Reg::url(), "seo_redirect");
if(!empty($to))
{
	header("Location: {$to}");
	exit();
}
?>