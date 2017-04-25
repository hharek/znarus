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

packjs("codemirror", ["name" => "content", "mime" => $mime]);

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