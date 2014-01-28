<?php
$page = Page::add
(
	$_POST['Name'], 
	$_POST['Url'],
	$_POST['Content'], 
	$_POST['Parent'], 
	$_POST['Html_Identified']
);

mess_ok("Страница «{$page['Name']}» добавлена успешно.");
redirect("#page/list");
?>