<?php
/**
 * Процедуры выполняемые в конце
 */
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
	"p"."Type" = 'end' AND
	"p"."Module_ID" = "m"."ID"
ORDER BY
	"p"."Identified" ASC
SQL;
$proc_end = Reg::db_core()->query_assoc($query, null, ["proc","module"]);

foreach ($proc_end as $val)
{
	require Reg::path_app() . "/{$val['Module_Type']}/{$val['Module_Identified']}/proc_end/{$val['Proc_Identified']}.php";
}
?>