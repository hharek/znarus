<?php
$html = ZN_Html::select_list();

/* Привязки */
foreach ($html as $key=>$val)
{
	$query = 
<<<SQL
SELECT
	COUNT(*) as count
FROM 
	"html_inc"
WHERE 
	"Html_ID" = $1
SQL;
	$inc_count = Reg::db_core()->query_one($query, $val['ID'], "html_inc");
	$html[$key]['inc_count'] = $inc_count;
}


Reg::path
([
	"Шаблоны [#html/list]"
]);
Reg::title("Шаблоны");
?>