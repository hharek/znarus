<?php
$html = _Html::get($_GET['id']);

path
([
	"Шаблоны [#html/list]",
	"{$html['Name']}"
]);

title("Редактирование шаблона «{$html['Name']}»");
?>