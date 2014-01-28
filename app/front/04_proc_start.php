<?php
/**
 * Процедуры выполняемые в начале
 */

/* Основные переменные для шаблона */
Reg::title(P::get("default_title"));					/* Заголовок */
Reg::content(T::get("default_content"));				/* Текст */

/* Процедуры */
$query =
<<<SQL
SELECT 
	"m"."Type" as "Module_Type", 
	"m"."Identified" as "Module_Identified", 
	"p"."Identified" as "Proc_Identified"
FROM 
	"proc" as "p",
	"module" as "m"
WHERE
	"p"."Type" = 'start' AND
	"p"."Active" = true AND
	"p"."Module_ID" = "m"."ID"
ORDER BY
	"p"."Identified" ASC
SQL;
$proc_start = Reg::db_core()->query_assoc($query, null, ["proc","module"]);

foreach ($proc_start as $val)
{
	call_user_func(function ($val)
	{		
		require Reg::path_app() . "/{$val['Module_Type']}/{$val['Module_Identified']}/proc_start/{$val['Proc_Identified']}.php";
	}, $val);
}
?>