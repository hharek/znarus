<?php
/* Включить chroot */
G::file()->chroot_enable();

/* Проверка */
if (empty($_POST['name']))
{
	throw new Exception("Имя папки не задано.");
}

if (!Chf::string($_POST['name']))
{
	throw new Exception("В имени папки присутствуют недопустимые символы");
}

/* Путь */
$path = "";
if ($_GET['path'] !== ".")
{
	$path = $_GET['path'] . "/";
}

/* Создать папку */
G::file()->mkdir($path . $_POST['name']);

/* Сообщение и перезагрузка */
mess_ok("Папка «{$_POST['name']}» создана.");
reload();
?>