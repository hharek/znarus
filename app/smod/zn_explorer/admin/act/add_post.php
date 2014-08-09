<?php
Err::check_field($_POST['file'], "string", false, "file", "Имя файла");
Err::exception();

if(!empty($_POST['path']) and !Chf::path($_POST['path']))
{throw new Exception_Admin("Путь задан неверно.");}

$file = $_POST['file'];
if(!empty($_POST['path']))
{
	$file = $_POST['path'] . "/" . $file;
}

Reg::file()->put($file, $_POST['content']);

mess_ok("Файл «{$file}» создан");
redirect("#zn_explorer/ls?path={$_POST['path']}");
?>