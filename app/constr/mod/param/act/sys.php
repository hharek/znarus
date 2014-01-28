<?php
$query =
<<<SQL
SELECT 
	"ID", 
	"Name", 
	"Identified", 
	"Type", 
	"Value"
FROM 
	"param"
WHERE 
	"Module_ID" IS NULL
ORDER BY 
	"Identified" ASC
SQL;
$param = Reg::db_core()->query_assoc($query, null, "param");

title("Системные параметры");
path
([
	"Системные параметры [#param/sys]"
]);
?>
