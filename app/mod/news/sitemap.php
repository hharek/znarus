<?php
/* Новости */
$query =
<<<SQL
SELECT  
	"Title", 
	"Url"
FROM 
	"news"
ORDER BY
	"Date" DESC
SQL;
$news = Reg::db()->query_assoc($query, null, "news");

/* Данные */
$child = [];
foreach ($news as $val)
{
	$child[] = 
	[
		"name" => $val['Title'],
		"url" => "/новости/" . $val['Url']
	];
}

$data = 
[
	[
		"name" => "Новости",
		"url" => "/новости",
		"child" => $child
	]
];

return $data;
?>