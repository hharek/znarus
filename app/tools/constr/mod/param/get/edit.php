<?php
$param = _Param::get($_GET['id']);
$module = _Module::get($param['Module_ID']);

title("Редактировать param «{$param['Name']}» у модуля «{$module['Name']}»");
path
([
	"Модули [#module/list]",
	"{$module['Name']} [#module/edit?id={$module['ID']}#tab_structure_param]",
	"Редактировать param «{$param['Name']}»"
]);
?>