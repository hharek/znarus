<?php
$news = News::select_line_by_id($_GET['id']);

title("Редактирование новости «{$news['Title']}»");
path
([
	"Новости [#news/list?year=".date("Y", strtotime($news['Date']))."]",
	"{$news['Title']} [#news/edit?id={$news['ID']}]"
]);
?>