<?php
$ajax = _Ajax::get($_GET['id']);
$module = _Module::get($ajax['Module_ID']);

title("Редактировать ajax «{$ajax['Name']}» у модуля «{$module['Name']}»");
path
([
	"Модули [#module/list]",
	"{$module['Name']} [#module/edit?id={$module['ID']}#tab_structure_ajax]",
	"Редактировать ajax «{$ajax['Name']}»"
]);
?>