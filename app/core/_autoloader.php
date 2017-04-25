<?php
/**
 * Автозагрузка классов ядра
 * 
 * @param string $class
 */
function autoloader_core($class)
{
	if(substr($class, 0, 1) == "_")
	{
		require DIR_APP . "/core/" . strtolower(substr($class, 1)).".php";
	}
	
	if($class === "P")
	{
		require DIR_APP . "/core/p.php";
	}
	
	if($class === "T")
	{
		require DIR_APP . "/core/t.php";
	}
	
	if($class === "Cache")
	{
		require DIR_APP . "/core/cache.php";
	}
}

spl_autoload_register("autoloader_core");
?>