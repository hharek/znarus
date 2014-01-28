<?php
$proc = ZN_Proc::select_line_by_id($_GET['id']);
$module = ZN_Module::select_line_by_id($proc['Module_ID']);

title("Редактировать proc_{$proc['Type']} «{$proc['Name']}» у модуля «{$module['Name']}»");
path
([
	"Модули [#module/list]",
	"{$module['Name']} [#module/edit?id={$module['ID']}#structure_proc_{$proc['Type']}]",
	"Редактировать proc_{$proc['Type']} «{$proc['Name']}» [#proc/edit?id={$proc['ID']}&type={$proc['Type']}]"
]);
?>