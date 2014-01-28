<?php
/* Путь */
$path = "";
$file_ar = explode("/", $_GET['file']);
if(count($file_ar) > 1)
{
	array_pop($file_ar);
	$path = implode("/", $file_ar);
}

/* Проверка */
if(!Reg::file()->is_file($_GET['file']))
{throw new Exception_Admin("Файл задан неверно.");}

/* Определить бинарный файл или нет */
$content = Reg::file()->get($_GET['file']);
if(substr_count($content, "\x00") > 0)
{throw new Exception_Admin("Невозможно править бинарные файлы");}

title("Редактировать файл «{$_GET['file']}»");
path
([
	"Проводник [#zn_explorer/ls?path={$path}]",
	"Редактировать файл «{$_GET['file']}» [#zn_explorer/put?file={$_GET['file']}]",
]);
?>