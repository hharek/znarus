<?php
$admin = ZN_Admin::select_line_by_id($_GET['id']);
$module = ZN_Module::select_line_by_id($admin['Module_ID']);

title("Редактировать admin «{$admin['Name']}» у модуля «{$module['Name']}»");
path
([
	"Модули [#module/list]",
	"{$module['Name']} [#module/edit?id={$module['ID']}#structure_admin]",
	"Редактировать admin «{$admin['Name']}» [#admin/edit?id={$admin['ID']}]"
]);
?>