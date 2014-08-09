<?php
$articles = Articles::select_line_by_id($_GET['id']);

title("Редактирование статьи «{$articles['Title']}»");
path
([
	"Статьи [#articles/list?year=".date("Y", strtotime($articles['Date']))."]",
	"{$articles['Title']} [#articles/edit?id={$articles['ID']}]"
]);
?>