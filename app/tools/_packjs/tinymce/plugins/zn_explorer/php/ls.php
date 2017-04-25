<?php
require "config.php";				/* Конфигурация */
require "explorer.php";				/* Проводник */

try
{
	/* Проверка конфигурации */
	ZN_Explorer::config_check();		
	
	$url = null;
	if (!empty($_GET['url']))
	{
		$url = $_GET['url'];
	}
	
	$type = "all";
	if (!empty($_GET['type']))
	{
		$type = $_GET['type'];
	}
	
	$data = ZN_Explorer::ls($url, $type);
	echo json_encode($data);

}
catch (Exception $e)
{
	echo json_encode(["error" => $e->getMessage()]);
}


?>