<?php
$text = _Text::get($_GET['id']);
$module = _Module::get($text['Module_ID']);

title("Редактировать text «{$text['Name']}» у модуля «{$module['Name']}»");
path
([
	"Модули [#module/list]",
	"{$module['Name']} [#module/edit?id={$module['ID']}#tab_structure_text]",
	"Редактировать text «{$text['Name']}»"
]);
?>