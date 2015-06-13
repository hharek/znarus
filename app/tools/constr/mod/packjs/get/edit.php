<?php
$packjs = _Packjs::get($_GET['id']);

title("Редактировать Пакет JavaScript «{$packjs['Name']}».");
path
([
	"Пакеты JavaScript [#packjs/list]",
	$packjs['Identified']
]);
?>