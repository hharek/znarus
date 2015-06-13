<?php
$articles = Articles::add
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
	"articles/" . $articles['ID'], 
	[
		"Date" => $_POST['Date'], 
		"Title" => $_POST['Title'], 
		"Url" => $_POST['Url'], 
		"Anons" => $_POST['Anons'], 
		"Content" => $_POST['Content'],
		"Tags" => $_POST['Tags']
	]
);

G::draft()->delete("articles/add");

mess_ok("Статья «{$articles['Title']}» добавлена");
redirect("#articles/list?year=" . date("Y", strtotime($articles['Date'])));
?>