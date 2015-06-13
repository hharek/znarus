<?php
header("Content-Type: text/plain");
require __DIR__ . "/../init.php";

try 
{
	
	
}
catch (Exception_Form $e)
{
	print_r(Err::get());
}
catch (Exception $e)
{
	echo $e->__toString();
}
?>