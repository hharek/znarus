<?php
$data = [];

/* Все страницы */
$query =
<<<SQL
SELECT
	"ID",
	"Name",
	"Url",
	"Content",
	"Parent"
FROM 
	"page"
ORDER BY
	"Name" ASC
SQL;
$page = Reg::db()->query_assoc($query, null, "page");

/**
 * Отобразить все данные по страницам
 * 
 * @param int $parent
 * @param string $url
 */
function page_get_data(&$page, $parent, $url)
{
	$data = [];
	foreach ($page as $key=>$val)
	{
		if((int)$val['Parent'] === (int)$parent)
		{
			$data[] = 
			[
				"url" => $url . "/" . $val['Url'],
				"name" => $val['Name'],
				"content" => $val['Content']
			];
			
			$child = page_get_data($page, $val['ID'], $url . "/" . $val['Url']);
			if(!empty($child))
			{
				$data = array_merge($data, $child);
			}
		}
	}
	
	return $data;
}

$data = page_get_data($page, 0, "");

/* Другие страницы */
$other = 
[
	[
		"url" => "/",
		"name" => P::get("page", "home_title"),
		"content" => T::get("page", "home_content")
	],
];

$data = array_merge($data, $other);

return $data;
?>