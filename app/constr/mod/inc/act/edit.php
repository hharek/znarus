<?php
$inc = ZN_Inc::select_line_by_id($_GET['id']);
$module = ZN_Module::select_line_by_id($inc['Module_ID']);

title("Редактировать inc «{$inc['Name']}» у модуля «{$module['Name']}»");
path
([
	"Модули [#module/list]",
	"{$module['Name']} [#module/edit?id={$module['ID']}#structure_inc]",
	"Редактировать inc «{$inc['Name']}» [#inc/edit?id={$inc['ID']}]"
]);
?>