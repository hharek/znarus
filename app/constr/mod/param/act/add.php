<?php
$module = ZN_Module::select_line_by_id($_GET['module_id']);

title("Добавить param к модулю «{$module['Name']}»");
path
([
	"Модули [#module/list]",
	"{$module['Name']} [#module/edit?id={$module['ID']}#structure_param]",
	"Добавить param [#param/add?module_id={$module['ID']}]"
]);
?>