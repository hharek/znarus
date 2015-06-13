<?php
$text = _Text::get($_GET['id']);

path
([
	"Системные тексты [#text/sys]",
	"{$text['Identified']}"
]);

title("Редактирование системного текста «{$text['Identified']}»");
?>