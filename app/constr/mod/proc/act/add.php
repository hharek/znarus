<?php
$module = ZN_Module::select_line_by_id($_GET['module_id']);

title("Добавить proc_{$_GET['type']} к модулю «{$module['Name']}»");
path
([
	"Модули [#module/list]",
	"{$module['Name']} [#module/edit?id={$module['ID']}#structure_proc_{$_GET['type']}]",
	"Добавить proc_{$_GET['type']} [#proc/add?module_id={$module['ID']}&type={$_GET['type']}]"
]);
?>