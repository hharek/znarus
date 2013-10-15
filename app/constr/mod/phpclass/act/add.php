<?php
$module = ZN_Module::select_line_by_id($_GET['module_id']);

title("Добавить phpclass к модулю «{$module['Name']}»");
path
([
	"Модули [#module/list]",
	"{$module['Name']} [#module/edit?id={$module['ID']}#structure_phpclass]",
	"Добавить phpclass [#phpclass/add?module_id={$module['ID']}]"
]);
?>