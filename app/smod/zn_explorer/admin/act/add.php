<?php
$path = "";
if($_GET['path'] !== ".")
{$path = $_GET['path'];}

if(!empty($path))
{
	title("Добавить файл в папку «{$path}»");
}
else
{
	title("Добавить файл");
}

path
([
	"Проводник [#zn_explorer/ls?path={$path}]",
	"Добавить файл [#zn_explorer/add?path={$path}]",
]);

?>