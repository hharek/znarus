<?php
$module = ZN_Module::add
(
	$_POST['Name'],
	$_POST['Identified'],
	$_POST['Desc'],
	$_POST['Version'],
	$_POST['Type'],
	$_POST['Pages_Isset'],
	$_POST['Active']
);

mess_ok("Создан модуль «{$module['Identified']} ({$module['Name']})».");
redirect("#module/list");
menu_top("module");
?>