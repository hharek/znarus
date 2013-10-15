<?php
$module = ZN_Module::select_line_by_id($_GET['module_id']);

title("Добавить text к модулю «{$module['Name']}»");
path
([
	"Модули [#module/list]",
	"{$module['Name']} [#module/edit?id={$module['ID']}#structure_text]",
	"Добавить text [#text/add?module_id={$module['ID']}]"
]);
?>