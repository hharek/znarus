<?php
$module = _Module::get($_GET['module_id']);

title("Добавить admin к модулю «{$module['Name']}»");
path
([
	"Модули [#module/list]",
	"{$module['Name']} [#module/edit?id={$module['ID']}#tab_structure_admin]",
	"Добавить admin"
]);
?>