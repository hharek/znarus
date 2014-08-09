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

mess_ok("Статья «{$articles['Title']}» добавлена");
redirect("#articles/list?year=" . date("Y", strtotime($articles['Date'])));
?>