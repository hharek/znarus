<?php
/* Включить chroot */
G::file()->chroot_enable();

$path = "";
if ($_GET['path'] !== ".")
{
	$path = $_GET['path'];
}

if (!empty($path))
{
	title("Добавить файл в папку «{$path}»");
}
else
{
	title("Добавить файл");
}

path
([
	"Проводник [#_explorer/ls?path={$path}]",
	"Добавить файл",
]);
?>