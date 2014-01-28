<?php
if(!empty($_POST['file_one']))
{
	header("Content-Disposition: attachment; filename=" . basename($_POST['file_one']));
	header("Content-Type: application/octet-stream");
	
	echo Reg::file()->get($_POST['file_one']);
}

if(!empty($_POST['file']))
{
	/* Путь */
	$path = "";
	if($_GET['path'] !== ".")
	{$path = $_GET['path']."/";}
	
	/* Файлы */
	$path_ar = array();
	foreach ($_POST['file'] as $val)
	{$path_ar[] = $path . $val;}
	
	/* Имя файла */
	$file_name = $_SERVER['SERVER_NAME'];
	if(!empty($path))
	{
		$file_name .= "_" . basename($path);
	}
	$file_name .= "_" . date("Y_m_d_H_i_s");
	$file_name .= ".zip";
	
	/* Выдать zip файл */
	Reg::file()->zip($path_ar, $file_name);
}
?>