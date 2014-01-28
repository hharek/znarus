<?php
$param = ZN_Param::select_line_by_id($_GET['id']);

path
([
	"Системные параметры [#param/sys]",
	"{$param['Identified']} [#param/edit?id={$_GET['id']}]"
]);

title("Редактирование системного параметра «{$param['Identified']}»");
?>