<?php
function zn_autoload($class)
{
	if(substr($class, 0, 3) == "ZN_")
	{
		require Reg::path_app() . "/core/" . strtolower(substr($class, 3)).".php";
	}
}

spl_autoload_register("zn_autoload");
?>