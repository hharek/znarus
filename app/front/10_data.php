<?php
try
{
	/* GET параметр «zn» */
	if(!isset($_GET['zn']))
	{ throw new Exception(); }
	
	/* Проверка указанного номера сессии */
	$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"user_session"
WHERE
	"ID" = $1
SQL;
	$session_count = Reg::db_core()->query_one($query, $_GET['zn'], "user_session");
	if((int)$session_count === 0)
	{ throw new Exception(); }
	
	/* Сведения по странице */
	header("Content-type: text/plain");
	
	$data = 
	[
		"html" => Reg::html(),
		"inc" => Reg::inc_data(),
		"module" => Reg::module(),
		"exe" => Reg::exe()
	];
	
	if(isset($_GET['serialize']))
	{ echo serialize($data); }
	else
	{ print_r($data); }
	
	exit();
}
catch (Exception $e)
{}
?>