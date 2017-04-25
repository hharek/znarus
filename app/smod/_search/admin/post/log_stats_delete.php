<?php
if (empty($_GET['query']))
{
	throw new Exception("Запрос не задан.");
}

$query = 
<<<SQL
DELETE
FROM
	"search_log"
WHERE
	"Query" = $1
SQL;
G::db_core()->query($query, $_GET['query']);

mess_ok("Удалено");
reload();
?>