<?php
$news = News::add
(
	$_POST['Date'], 
	$_POST['Title'], 
	$_POST['Url'], 
	$_POST['Anons'], 
	$_POST['Content'],
	$_POST['Tags']
);

G::version()->set
(
	"news/" . $news['ID'], 
	[
		"Date" => $_POST['Date'], 
		"Title" => $_POST['Title'], 
		"Url" => $_POST['Url'], 
		"Anons" => $_POST['Anons'], 
		"Content" => $_POST['Content'],
		"Tags" => $_POST['Tags']
	]
);
G::draft()->delete("news/add");

mess_ok("Новость «{$news['Title']}» добавлена");
redirect("#news/list?year=" . date("Y", strtotime($news['Date'])));
?>