<?php
/* Включить chroot */
G::file()->chroot_enable();

/* Удалить один файл */
if (!empty($_GET['file']))
{
	G::file()->rm($_GET['file']);
}
/* Удалить несколько файлов */
else if (!empty($_POST['file']))
{
	$path = "";
	if ($_GET['path'] !== ".")
	{
		$path = $_GET['path'] . "/";
	}

	foreach ($_POST['file'] as $val)
	{
		G::file()->rm($path . $val);
	}
}

/* Сообщение и перезагрузка */
mess_ok("Файлы удалены");
reload();
?>