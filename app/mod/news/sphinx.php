<?php
$data = [];

/* Страница "Новости" */
$data[] = 
[
	"url" => "/новости",
	"name" => "Новости",
	"content" => "Последние новости. Новости сайта"
];

/* Страницы с новостями */
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
	"news"
ORDER BY
	"Date" DESC
SQL;
$news = Reg::db()->query_assoc($query, null, "news");

foreach ($news as $val)
{
	$date = date("d.m.Y", strtotime($val['Date']));
	$data[] = 
	[
		"url" => "/новости/" . $val['Url'],
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