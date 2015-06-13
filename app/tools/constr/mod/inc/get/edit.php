<?php
$inc = _Inc::get($_GET['id']);
$module = _Module::get($inc['Module_ID']);

title("Редактировать inc «{$inc['Name']}» у модуля «{$module['Name']}»");
path
([
	"Модули [#module/list]",
	"{$module['Name']} [#module/edit?id={$module['ID']}#tab_structure_inc]",
	"Редактировать inc «{$inc['Name']}»"
]);
?>