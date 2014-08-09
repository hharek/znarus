<?php
/* Новости */
$query =
<<<SQL
SELECT
	"Url"
FROM 
	"news"
ORDER BY
	"Date" DESC
SQL;
$news = Reg::db()->query_assoc($query, null, "news");

/* Данные */
$data = 
[
	[
		"url" => "/новости"
	]
];

foreach ($news as $val)
{
	$data[] = 
	[
		"url" => "/новости/" . $val['Url']
	];
}

return $data;
?>