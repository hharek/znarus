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

mess_ok("Новость «{$news['Title']}» добавлена");
redirect("#news/list?year=" . date("Y", strtotime($news['Date'])));
?>