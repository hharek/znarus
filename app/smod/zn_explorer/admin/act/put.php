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

/* Расширение файла */
$ext = ""; $mime = "";
$ext_pos = strrpos($_GET['file'], ".");
if($ext_pos !== false)
{
	$ext = mb_substr($_GET['file'], $ext_pos);
	switch ($ext)
	{
		case ".css" : $mime = "text/css"; break;
		case ".html" : $mime = "application/x-httpd-php"; break;
		case ".htm" : $mime = "application/x-httpd-php"; break;
		case ".js" : $mime = "javascript"; break;
		case ".php" : $mime = "application/x-httpd-php"; break;
	}
}


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