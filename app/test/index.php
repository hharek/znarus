<?php
header("Content-Type: text/plain");
require dirname(__FILE__)."/init.php";

try 
{
	header("Content-type: text/plain");
	
	require_once Reg::path_app() . "/mod/menu/class/menu.php";
	require_once Reg::path_app() . "/mod/menu/class/menu_item.php";
	
	
	
	
	
	
	
	
	
	
	
}
catch (Exception $e)
{
	echo $e->__toString();
	
	if(count(Err::get()) != 0)
	{
		echo "\n";
		print_r(Err::get());
	}
}
?>