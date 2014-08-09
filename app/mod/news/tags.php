<?php
$data = [];

/* Страница "новости" */
$data[] = 
[
	"url" => "/новости",
	"name" => "Новости",
	"content" => "Последние новости. Новости сайта",
	"tags" => "новости, новости сайта, последние новости"
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
	"Content",
	"Tags"
FROM 
	"news"
WHERE
	"Tags" != ''
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