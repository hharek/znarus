<?php
/* Исполнитель */
$sql_from = "";
if(isset($_GET['from']) and $_GET['from'] !== "")
{
	if(!Chf::string($_GET['from']))
	{
		throw new Exception("Исполнитель указан неверно.");
	}
	$from = $_GET['from'];
	$sql_from = 
<<<SQL
AND COALESCE ("From", 0) = '{$_GET['from']}'
SQL;
}

/* Заказчик */
$sql_to = "";
if(isset($_GET['to']) and $_GET['to'] !== "")
{
	if(!Chf::string($_GET['to']))
	{
		throw new Exception("Заказчик указан неверно.");
	}
	$to = $_GET['to'];
	$sql_to = 
<<<SQL
AND COALESCE ("To", 0) = '{$_GET['to']}'
SQL;
}

/* Заголовок */
path
([
	"Задания"
]);

title("Задания");

/* Задания  */
$query = 
<<<SQL
SELECT
	"t"."ID",
	COALESCE("t"."From", 0) as "From",
	"u1"."Name" as "From_Name",
	COALESCE("t"."To", 0) as "To",
	"u2"."Name" as "To_Name",
	"t"."Name",
	"t"."Content",
	"t"."Status",
	"t"."Date_Create",
	"t"."Date_Require",
	"t"."Date_Done",
	"t"."Date_Fail"
FROM 
	"task" as "t" LEFT JOIN 
	"user" as "u1" ON ("t"."From" = "u1"."ID") LEFT JOIN
	"user" as "u2" ON ("t"."To" = "u2"."ID")
WHERE true
	{$sql_from}
	{$sql_to}
ORDER BY 
	"t"."Date_Create" DESC
SQL;
$task = G::db_core()->query($query)->assoc();

/* Пользователи */
$user = _User::get_all();
?>