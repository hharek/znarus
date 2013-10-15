<?php
$param = ZN_Param::select_line_by_id($_GET['id']);
$module = ZN_Module::select_line_by_id($param['Module_ID']);

title("Редактировать param «{$param['Name']}» у модуля «{$module['Name']}»");
path
([
	"Модули [#module/list]",
	"{$module['Name']} [#module/edit?id={$module['ID']}#structure_param]",
	"Редактировать param «{$param['Name']}» [#param/edit?id={$param['ID']}]"
]);
?>