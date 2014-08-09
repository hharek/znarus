<?php
if(!Chf::string($_POST['name']))
{throw new Exception_Admin("В имени файла присутствуют недопустимые символы. {$_POST['name']}");}

/* Путь */
$path = "";
$file_ar = explode("/", $_GET['file']);
if(count($file_ar) > 1)
{
	array_pop($file_ar);
	$path = implode("/", $file_ar) . "/";
}

/* Переименовать */
Reg::file()->mv($_GET['file'], $path . $_POST['name']);

reload();
?>