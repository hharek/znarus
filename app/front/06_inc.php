<?php
/**
 * Составные части шаблона
 */

/* Инки прикриплённые к шаблону */
$query =
<<<SQL
SELECT 
	"i"."ID",
	"i"."Identified",
	"m"."Type" as "Module_Type",
	"m"."Identified" as "Module_Identified"
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
Reg::inc_data($inc);

/* Запуск */
$inc_content = array();
foreach ($inc as $_inc_val)
{
	/* Классы */
	$path = Reg::path_app() . "/" . $_inc_val['Module_Type'] . "/" . $_inc_val['Module_Identified'] . "/class";
	if(is_dir($path))
	{
		$files = scandir($path);
		foreach ($files as $f_val)
		{
			if (is_file($path . "/" . $f_val) and mb_substr($f_val, -4) === ".php")
			{require_once $path . "/" . $f_val;}
		}
	}
	
	ob_start();
	call_user_func(function ($_inc_val)
	{		
		require Reg::path_app() . "/{$_inc_val['Module_Type']}/{$_inc_val['Module_Identified']}/inc/act/{$_inc_val['Identified']}.php";
		require Reg::path_app() . "/{$_inc_val['Module_Type']}/{$_inc_val['Module_Identified']}/inc/html/{$_inc_val['Identified']}.html";
	}, $_inc_val);
	$inc_content["<!--zn_" . $_inc_val['Module_Identified'] . "_" . $_inc_val['Identified'] . "-->"] = ob_get_contents();
	ob_end_clean();
}
Reg::inc($inc_content);
?>