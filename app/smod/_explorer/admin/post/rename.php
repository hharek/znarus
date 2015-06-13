<?php
/* Включить chroot */
G::file()->chroot_enable();

if(!Chf::string($_POST['name']))
{
	throw new Exception("В имени файла присутствуют недопустимые символы. {$_POST['name']}");
}

/* Путь */
$path = "";
$file_ar = explode("/", $_GET['file']);
if(count($file_ar) > 1)
{
	array_pop($file_ar);
	$path = implode("/", $file_ar) . "/";
}

/* Переименовать */
G::file()->mv($_GET['file'], $path . $_POST['name']);

/* Сообщение и перезагрузка */
mess_ok("Файл переименован");
reload();
?>