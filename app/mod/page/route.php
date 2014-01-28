<?php
$query =
<<<SQL
SELECT 
	"ID", 
	"Url", 
	COALESCE("Parent", 0) as "Parent"
FROM 
	"page"
SQL;
$page = Reg::db()->query_assoc($query, null, "page");

$parent = 0; $page_isset = true;
foreach (Reg::url_path_ar() as $url)
{
	$url_part_isset = false;
	foreach ($page as $val)
	{
		if($url === $val['Url'] and $parent === (int)$val['Parent'])
		{
			$url_part_isset = true;
			$parent = (int)$val['ID'];
			break;
		}
	}
	
	if($url_part_isset === false)
	{
		$page_isset = false;
		break;
	}
}

if($page_isset === true)
{
	Reg::page_id((int)$val['ID']);
	return "content";
}

?>