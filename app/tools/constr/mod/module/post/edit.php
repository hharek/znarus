<?php
if ($_POST['Access_Enable'] === "1" and empty($_POST['Access']))
{
	throw new Exception("Укажите тип влияния на доступ.");
}

$access = "no";
if ($_POST['Access_Enable'] === "1")
{
	$access = $_POST['Access'];
}

$module = _Module::edit
(
	$_GET['id'], 
	$_POST['Name'], 
	$_POST['Identified'], 
	$_POST['Description'], 
	$_POST['Version'],
	$access,
	$_POST['Page_Info_Function'],
	$_POST['Active']
);

mess_ok("Модуль «{$module['Identified']} ({$module['Name']})» отредактирован.");
?>