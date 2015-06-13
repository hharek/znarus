<?php
$module = _Module::add
(
	$_POST['Name'],
	$_POST['Identified'],
	$_POST['Description'],
	$_POST['Version']
);

mess_ok("Создан модуль «{$module['Identified']} ({$module['Name']})».");
redirect("#module/list");
?>