<?php
if (isset($_GET['ok']))
{
	_Packjs::dump($_GET['id']);
	exit();
}

$packjs = _Packjs::get($_GET['id']);

title("Создать установочный файл для «{$packjs['Identified']}».");
path
([
	"Пакеты JavaScript [#packjs/list]",
	"Создать установочный файл для" . $packjs['Identified']
]);
?>