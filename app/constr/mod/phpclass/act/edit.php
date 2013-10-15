<?php
$phpclass = ZN_Phpclass::select_line_by_id($_GET['id']);
$module = ZN_Module::select_line_by_id($phpclass['Module_ID']);

title("Редактировать phpclass «{$phpclass['Name']}» у модуля «{$module['Name']}»");
path
([
	"Модули [#module/list]",
	"{$module['Name']} [#module/edit?id={$module['ID']}#structure_phpclass]",
	"Редактировать phpclass «{$phpclass['Name']}» [#phpclass/edit?id={$phpclass['ID']}]"
]);
?>