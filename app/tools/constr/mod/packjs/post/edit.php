<?php
$packjs = _Packjs::edit
(
	$_GET['id'], 
	$_POST['Identified'], 
	$_POST['Name'], 
	$_POST['Description'], 
	$_POST['Version'], 
	$_POST['Url'], 
	$_POST['Category'], 
	$_POST['Depend']
);

mess_ok("Пакет «{$packjs['Name']}» отредактирован.");
?>