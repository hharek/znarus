<?php
$ajax = _Ajax::add
(
	$_POST['Identified'], 
	$_POST['Name'], 
	$_POST['Data_Type'],
	$_POST['Get'], 
	$_POST['Post'], 
	$_GET['module_id']
);

mess_ok("ajax «{$ajax['Identified']} ({$ajax['Name']})» добавлен успешно.");
redirect("#module/edit?id={$_GET['module_id']}#tab_structure_ajax");
?>