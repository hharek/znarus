<?php
$text = ZN_Text::select_line_by_id($_GET['id']);
$module = ZN_Module::select_line_by_id($text['Module_ID']);

title("Редактировать text «{$text['Name']}» у модуля «{$module['Name']}»");
path
([
	"Модули [#module/list]",
	"{$module['Name']} [#module/edit?id={$module['ID']}#structure_text]",
	"Редактировать text «{$text['Name']}» [#text/edit?id={$text['ID']}]"
]);
?>