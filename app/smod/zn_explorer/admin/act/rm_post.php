<?php
/* Удалить один файл */
if(isset($_GET['file']))
{
	/* Удалить */
	Reg::file()->rm($_GET['file']);
}
/* Удалить несколько файлов */
else if(isset($_POST['file']))
{
	$path = "";
	if($_GET['path'] !== ".")
	{$path = $_GET['path']."/";}
	
	foreach ($_POST['file'] as $val)
	{
		Reg::file()->rm($path . $val);
	}
}

reload();
?>