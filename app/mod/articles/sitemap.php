<?php
/* Статьи */
$query =
<<<SQL
SELECT  
	"Title", 
	"Url"
FROM 
	"articles"
ORDER BY
	"Date" DESC
SQL;
$articles = Reg::db()->query_assoc($query, null, "articles");

/* Данные */
$child = [];
foreach ($articles as $val)
{
	$child[] = 
	[
		"name" => $val['Title'],
		"url" => "/статьи/" . $val['Url']
	];
}

$data = 
[
	[
		"name" => "Статьи",
		"url" => "/статьи",
		"child" => $child
	]
];

return $data;
?>