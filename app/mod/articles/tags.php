<?php
$data = [];

/* Страница "Статьи" */
$data[] = 
[
	"url" => "/статьи",
	"name" => "Статьи",
	"content" => "Полезные статьи",
	"tags" => "статьи, полезные статьи"
];

/* Страницы со статьями */
$query =
<<<SQL
SELECT 
	"ID",
	"Date",
	"Title",
	"Url",
	"Anons",
	"Content",
	"Tags"
FROM 
	"articles"
WHERE
	"Tags" != ''
ORDER BY
	"Date" DESC
SQL;
$articles = Reg::db()->query_assoc($query, null, "articles");

foreach ($articles as $val)
{
	$date = date("d.m.Y", strtotime($val['Date']));
	$data[] = 
	[
		"url" => "/статьи/" . $val['Url'],
		"name" => $val['Title'],
		"tags" => $val['Tags'],
		"content" => 
<<<HTML
{$val['Content']}
Дата: {$date}
Анонс: {$val['Anons']}
HTML
		

	];
}

return $data;
?>