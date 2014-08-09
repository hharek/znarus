<?php
$articles = Articles::edit
(
	$_GET['id'], 
	$_POST['Date'], 
	$_POST['Title'], 
	$_POST['Url'], 
	$_POST['Anons'], 
	$_POST['Content'],
	$_POST['Tags']
);

mess_ok("Статья «{$articles['Title']}» отредактирована");
?>