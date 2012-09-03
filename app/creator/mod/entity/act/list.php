<?php
$pack = ZN_Pack::select_line_by_id($_GET['pack_id']);
$entity = ZN_Entity::select_list_by_pack_id($_GET['pack_id']);

foreach ($entity as $key=>$val)
{
	$query = 
<<<SQL
SELECT COUNT(*) as count
FROM "{$val['Table']}"
SQL;
	$count = Reg::db()->query_one($query);
	$entity[$key]['count'] = $count;
}
?>
