<?php
function zn_autoload($class)
{
	if(substr($class, 0, 3) == "ZN_")
	{
		require Reg::path_app() . "/core/" . strtolower(substr($class, 3)).".php";
	}
	
	if($class === "P")
	{
		require Reg::path_app() . "/core/p.php";
	}
	
	if($class === "T")
	{
		require Reg::path_app() . "/core/t.php";
	}
}

spl_autoload_register("zn_autoload");
?>