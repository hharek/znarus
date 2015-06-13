<?php
/* Включить chroot */
G::file()->chroot_enable();

if (empty($_FILES['file']['name'][0]))
{
	throw new Exception("Файлы не заданы");
}

/* Путь */
$path = "";
if ($_POST['path'] !== ".")
{
	$path = $_POST['path'] . "/";
}

/* Закачать */
foreach ($_FILES['file']['name'] as $key => $val)
{
	G::file()->upload($_FILES['file']['tmp_name'][$key], $path . $_FILES['file']['name'][$key], true);
}
mess_ok("Файлы закачаны");
reload();
?>