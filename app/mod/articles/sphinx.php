<?php
$data = [];

/* Страница "Статьи" */
$data[] = 
[
	"url" => "/статьи",
	"name" => "Статьи",
	"content" => "Полезные статьи"
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
	"Content"
FROM 
	"articles"
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