<?php
$html = ZN_Html::select_line_by_id($_GET['id']);

path
([
	"Шаблоны [#html/list]",
	"{$html['Name']} [#html/edit?id={$_GET['id']}]"
]);

title("Редактирование шаблона «{$html['Name']}»");

/* Привязанные инки */
$query =
<<<SQL
SELECT 
	"i"."ID" as "Inc_ID",
	"i"."Name" as "Inc_Name",
	"i"."Identified" as "Inc_Identified",
	"m"."ID" as "Module_ID",
	"m"."Name" as "Module_Name",
	"m"."Identified" as "Module_Identified"
FROM 
	"html_inc" as "hi", 
	"inc" as "i",
	"module" as "m"
WHERE 
	"hi"."Html_ID" = $1 AND
	"hi"."Inc_ID" = "i"."ID" AND
	"i"."Module_ID" = "m"."ID"
SQL;
$html_inc = Reg::db_core()->query_assoc($query, $html['ID'], ["html_inc","inc","module"]);

/* Не привязанные инки */
$query =
<<<SQL
SELECT 
	"i"."ID" as "Inc_ID",
	"i"."Name" as "Inc_Name",
	"i"."Identified" as "Inc_Identified",
	"m"."ID" as "Module_ID",
	"m"."Name" as "Module_Name",
	"m"."Identified" as "Module_Identified"
FROM 
	"inc" as "i",
	"module" as "m"
WHERE 
	"i"."ID" NOT IN
	(
		SELECT "Inc_ID"
		FROM "html_inc"
		WHERE "Html_ID" = $1
	) AND
	"i"."Module_ID" = "m"."ID" 
SQL;
$html_inc_allow = Reg::db_core()->query_assoc($query, $html['ID'], ["inc","module"]);

?>