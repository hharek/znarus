<?php
$html = ZN_Html::select_list();

$query =
<<<SQL
SELECT 
	"i"."ID", 
	"i"."Identified", 
	"i"."Name",
	"m"."ID" as "Module_ID",
	"m"."Identified" as "Module_Identified",
	"m"."Name" as "Module_Name",
	"hi"."Html_ID"
FROM 
	"html_inc" as "hi", 
	"inc" as "i",
	"module" as "m"
WHERE 
	"hi"."Inc_ID" = "i"."ID" AND
	"i"."Module_ID" = "m"."ID"
ORDER BY
	"i"."Identified" ASC
SQL;
$inc = Reg::db_core()->query_assoc($query, null, ["html_inc","inc"]);

foreach ($html as $h_key=>$h_val)
{
	$html[$h_key]['Inc'] = [];
	foreach ($inc as $i_key=>$i_val)
	{
		if($i_val['Html_ID'] === $h_val['ID'])
		{
			$html[$h_key]['Inc'][] = $i_val;
		}
	}
}

?>