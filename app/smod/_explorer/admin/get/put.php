<?php
/* Включить chroot */
G::file()->chroot_enable();

/* Путь */
$path = "";
$file_ar = explode("/", $_GET['file']);
if(count($file_ar) > 1)
{
	array_pop($file_ar);
	$path = implode("/", $file_ar);
}

/* Проверка */
if(!G::file()->is_file($_GET['file']))
{
	throw new Exception("Файл задан неверно.");
}

/* Определить бинарный файл или нет */
$content = G::file()->get($_GET['file']);
if(substr_count($content, "\x00") > 0)
{
	throw new Exception("Невозможно править бинарные файлы");
}

title("Редактировать файл «{$_GET['file']}»");
path
([
	"Проводник [#_explorer/ls?path={$path}]",
	"Редактировать файл «{$_GET['file']}» [#_explorer/put?file={$_GET['file']}]",
]);
?>