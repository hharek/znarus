<?php
$packjs = _Packjs::add
(
	$_POST['Identified'], 
	$_POST['Name'], 
	$_POST['Description'], 
	$_POST['Version'], 
	$_POST['Url'], 
	$_POST['Category'], 
	$_POST['Depend']
);

mess_ok("Пакет {$packjs['Identified']} ({$packjs['Name']}) добавлен.");
redirect("#packjs/list");
?>