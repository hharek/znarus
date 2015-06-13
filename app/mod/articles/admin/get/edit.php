<?php
$articles = Articles::get($_GET['id']);

title("Редактирование статьи «{$articles['Title']}»");
path
([
	"Статьи [#articles/list?year=".date("Y", strtotime($articles['Date']))."]",
	"{$articles['Title']}"
]);
	
version("articles/{$articles['ID']}");
autosave();

packjs("editor", ["name" => "Content"]);
packjs("calendar", ["name" => "Date"])
?>