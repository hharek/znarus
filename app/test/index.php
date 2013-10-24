<?php
header("Content-Type: text/plain");
require dirname(__FILE__)."/init.php";

try 
{
	header("Content-type: text/plain");
	

	ZN_Admin::delete(2);
	ZN_Admin::delete(3);
	ZN_Admin::delete(4);
	ZN_Admin::delete(5);
	ZN_Admin::delete(6);
	
	
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