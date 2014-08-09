<?php
/* Статьи */
$query =
<<<SQL
SELECT
	"Url"
FROM 
	"articles"
ORDER BY
	"Date" DESC
SQL;
$articles = Reg::db()->query_assoc($query, null, "articles");

/* Данные */
$data = 
[
	[
		"url" => "/статьи"
	]
];

foreach ($articles as $val)
{
	$data[] = 
	[
		"url" => "/статьи/" . $val['Url']
	];
}

return $data;
?>