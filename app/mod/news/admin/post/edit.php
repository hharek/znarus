<?php
$news = News::edit
(
	$_GET['id'], 
	$_POST['Date'],
	$_POST['Title'], 
	$_POST['Url'], 
	$_POST['Anons'], 
	$_POST['Content'],
	$_POST['Tags']
);

if(!isset($_GET['autosave']))
{
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
}

mess_ok("Новость «{$news['Title']}» отредактирована");
?>