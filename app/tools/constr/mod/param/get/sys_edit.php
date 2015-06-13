<?php
$param = _Param::get($_GET['id']);

path
([
	"Системные параметры [#param/sys]",
	"{$param['Identified']}"
]);

title("Редактирование системного параметра «{$param['Identified']}»");
?>