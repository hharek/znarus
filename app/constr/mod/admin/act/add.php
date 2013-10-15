<?php
$module = ZN_Module::select_line_by_id($_GET['module_id']);

title("Добавить admin к модулю «{$module['Name']}»");
path
([
	"Модули [#module/list]",
	"{$module['Name']} [#module/edit?id={$module['ID']}#structure_admin]",
	"Добавить admin [#admin/add?module_id={$module['ID']}]"
]);
?>