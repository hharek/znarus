<?php
header("Content-Type: text/plain");
require dirname(__FILE__)."/init.php";

try 
{
	header("Content-type: text/plain");
	
	
	
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