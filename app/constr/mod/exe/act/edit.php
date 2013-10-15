<?php
$exe = ZN_Exe::select_line_by_id($_GET['id']);
$module = ZN_Module::select_line_by_id($exe['Module_ID']);

title("Редактировать exe «{$exe['Name']}» у модуля «{$module['Name']}»");
path
([
	"Модули [#module/list]",
	"{$module['Name']} [#module/edit?id={$module['ID']}#structure_exe]",
	"Редактировать exe «{$exe['Name']}» [#exe/edit?id={$exe['ID']}]"
]);
?>