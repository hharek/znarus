<?php
$text = ZN_Text::select_line_by_id($_GET['id']);

path
([
	"Системные тексты [#text/sys]",
	"{$text['Identified']} [#text/edit?id={$_GET['id']}]"
]);

title("Редактирование системного текста «{$text['Identified']}»");
?>