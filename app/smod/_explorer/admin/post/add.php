<?php
/* Включить chroot */
G::file()->chroot_enable();

Err::check_field($_POST['file'], "string", false, "file", "Имя файла");
Err::exception();

if (!empty($_POST['path']) and ! Type::check("path", $_POST['path']))
{
	throw new Exception("Путь задан неверно.");
}

$file = $_POST['file'];
if (!empty($_POST['path']))
{
	$file = $_POST['path'] . "/" . $file;
}

G::file()->put($file, $_POST['content']);

mess_ok("Файл «{$file}» создан");
redirect("#_explorer/ls?path={$_POST['path']}");
?>