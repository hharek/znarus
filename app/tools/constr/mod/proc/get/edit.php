<?php
$proc = _Proc::get($_GET['id']);
$module = _Module::get($proc['Module_ID']);

title("Редактировать proc «{$proc['Name']}» у модуля «{$module['Name']}»");
path
([
	"Модули [#module/list]",
	"{$module['Name']} [#module/edit?id={$module['ID']}#tab_structure_proc]",
	"Редактировать proc «{$proc['Name']}»"
]);
?>