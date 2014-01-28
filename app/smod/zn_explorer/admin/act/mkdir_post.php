<?php
/* Проверка */
if(empty($_POST['name']))
{throw new Exception_Admin("Имя папки не задано.");}

if(!Chf::file($_POST['name']))
{throw new Exception_Admin("В имени папки присутствуют недопустимые символы");}

/* Путь */
$path = "";
if($_GET['path'] !== ".")
{$path = $_GET['path']."/";}
	
/* Создать папку */
Reg::file()->mkdir($path . $_POST['name']);

reload();
?>