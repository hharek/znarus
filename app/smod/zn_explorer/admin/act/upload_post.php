<?php
if(empty($_FILES))
{throw new Exception_Admin("Файлы не заданы");}

/* Путь */
$path = "";
if($_POST['path'] !== ".")
{$path = $_POST['path'] . "/";}

/* Закачать */
foreach ($_FILES['file']['name'] as $key=>$val)
{
	Reg::file()->upload($_FILES['file']['tmp_name'][$key], $path . $_FILES['file']['name'][$key], true);
}
reload();

?>