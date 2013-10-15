<?php
$html = ZN_Html::select_line_by_id($_GET['id']);

path
([
	"Шаблоны [#html/list]",
	"{$html['Name']} [#html/edit?id={$_GET['id']}]"
]);

title("Редактирование шаблона «{$html['Name']}»");
?>