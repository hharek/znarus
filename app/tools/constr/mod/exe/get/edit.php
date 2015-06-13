<?php
$exe = _Exe::get($_GET['id']);
$module = _Module::get($exe['Module_ID']);

title("Редактировать exe «{$exe['Name']}» у модуля «{$module['Name']}»");
path
([
	"Модули [#module/list]",
	"{$module['Name']} [#module/edit?id={$module['ID']}#tab_structure_exe]",
	"Редактировать exe «{$exe['Name']}»"
]);
?>