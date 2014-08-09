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

mess_ok("Новость «{$news['Title']}» отредактирована");
?>