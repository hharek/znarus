<?php
$query =
<<<SQL
SELECT 
	"ID", 
	"Name", 
	"Identified"
FROM 
	"text"
WHERE 
	"Module_ID" IS NULL
ORDER BY 
	"Identified" ASC
SQL;
$text = Reg::db_core()->query_assoc($query, null, "text");

title("Системные тексты");
path
([
	"Системные тексты [#text/sys]"
]);
?>