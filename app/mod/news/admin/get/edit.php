<?php
$news = News::get($_GET['id']);

version("news/{$news['ID']}");
autosave();

title("Редактирование новости «{$news['Title']}»");
path
([
	"Новости [#news/list?year=".date("Y", strtotime($news['Date']))."]",
	$news['Title']
]);

packjs("editor", ["name" => "Content"]);
packjs("datepicker", ["name" => "Date"]);
?>