<?php
$page = Page::add
(
	$_POST['Name'], 
	$_POST['Url'],
	$_POST['Content'],
	$_POST['Tags'],
	$_POST['Parent'], 
	$_POST['Html_ID']
);

G::version()->set
(
	"page/" . $page['ID'], 
	[
		"Name" => $_POST['Name'], 
		"Url" => $_POST['Url'],
		"Content" => $_POST['Content'],
		"Tags" => $_POST['Tags']
	]
);
G::draft()->delete("page/add");

mess_ok("Страница «{$page['Name']}» добавлена успешно.");
redirect("#page/list");
?>