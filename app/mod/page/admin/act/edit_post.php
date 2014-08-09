<?php
$page = Page::edit
(
	$_GET['id'],
	$_POST['Name'], 
	$_POST['Url'],
	$_POST['Content'],
	$_POST['Tags'],
	$_POST['Parent'], 
	$_POST['Html_Identified']
);

mess_ok("Страница «{$page['Name']}» отредактирована успешно.");
//reload();
?>