<?php
$html = _Html::get($_GET['id']);

G::file_app()->put("html/{$html['Identified']}.html", $_POST['Content']);

_Cache_Front::delete(["html" => $html['Identified']]);			/* Удалить кэш */

G::version()->set
(
	"_html_code/html_{$html['ID']}", 
	[
		"Content" => $_POST['Content']
	]
);

mess_ok("Шаблон «{$html['Identified']}.html сохранён.»");
?>