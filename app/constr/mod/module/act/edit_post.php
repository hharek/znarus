<?php
$module = ZN_Module::edit
(
	$_GET['id'], 
	$_POST['Name'], 
	$_POST['Identified'], 
	$_POST['Desc'], 
	$_POST['Version'],
	$_POST['Pages_Isset'],
	$_POST['Active']
);

mess_ok("Модуль «{$module['Identified']} ({$module['Name']})» отредактирован.");
require "edit.php";
menu_top("module");
?>