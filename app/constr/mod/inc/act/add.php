<?php
$module = ZN_Module::select_line_by_id($_GET['module_id']);

title("Добавить inc к модулю «{$module['Name']}»");
path
([
	"Модули [#module/list]",
	"{$module['Name']} [#module/edit?id={$module['ID']}#structure_inc]",
	"Добавить inc [#inc/add?module_id={$module['ID']}]"
]);
?>