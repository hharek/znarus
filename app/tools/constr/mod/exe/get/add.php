<?php
$module = _Module::get($_GET['module_id']);

title("Добавить exe к модулю «{$module['Name']}»");
path
([
	"Модули [#module/list]",
	"{$module['Name']} [#module/edit?id={$module['ID']}#tab_structure_exe]",
	"Добавить exe"
]);
?>