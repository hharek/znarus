<?php
/**
 * Составные части шаблона
 */

/* Инки прикриплённые к шаблону */
$query =
<<<SQL
SELECT 
	"m"."Type",
	"m"."Identified" as "Module_Identified",
	"i"."Identified" as "Inc_Identified"
FROM 
	"html_inc" as "hi",
	"html" as "h",
	"inc" as "i",
	"module" as "m"
WHERE 
	"hi"."Html_ID" = "h"."ID" AND
	"h"."Identified" = $1 AND
	"hi"."Inc_ID"  = "i"."ID" AND
	"i"."Module_ID" = "m"."ID"
SQL;
$inc = Reg::db_core()->query_assoc($query, Reg::html(), ["html_inc","html","inc","module"]);

/* Запуск */
$inc_content = array();
foreach ($inc as $val)
{
	/* Классы */
	$path = Reg::path_app() . "/" . $val['Type'] . "/" . $val['Module_Identified'] . "/class";
	$files = scandir($path);
	foreach ($files as $f_val)
	{
		if (is_file($path . "/" . $f_val) and mb_substr($f_val, -4) === ".php")
		{require_once $path . "/" . $f_val;}
	}
	
	ob_start();
	call_user_func(function ($val)
	{		
		require Reg::path_app() . "/{$val['Type']}/{$val['Module_Identified']}/inc/act/{$val['Inc_Identified']}.php";
		require Reg::path_app() . "/{$val['Type']}/{$val['Module_Identified']}/inc/html/{$val['Inc_Identified']}.html";
	}, $val);
	$inc_content["<!--zn_" . $val['Module_Identified'] . "_" . $val['Inc_Identified'] . "-->"] = ob_get_contents();
	ob_end_clean();
}
Reg::inc($inc_content);
?>