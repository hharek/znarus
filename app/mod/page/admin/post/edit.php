<?php
$page = Page::edit
(
	$_GET['id'],
	$_POST['Name'], 
	$_POST['Url'],
	$_POST['Content'],
	$_POST['Tags'],
	$_POST['Parent'], 
	$_POST['Html_ID']
);

if(!isset($_GET['autosave']))
{
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
}

mess_ok("Страница «{$page['Name']}» сохранена.");
?>