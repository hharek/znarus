<?php
$admin = _Admin::get($_GET['id']);
$module = _Module::get($admin['Module_ID']);

title("Редактировать admin «{$admin['Name']}» у модуля «{$module['Name']}»");
path
([
	"Модули [#module/list]",
	"{$module['Name']} [#module/edit?id={$module['ID']}#tab_structure_admin]",
	"Редактировать admin «{$admin['Name']}»"
]);
?>