<?php
set_time_limit(0);
header("Content-Type: text/plain");
require __DIR__ . "/../init.php";

try 
{

	
	
	
	
}
catch (Exception_Form $e)
{
	print_r(Err::get());
}
catch (Exception_Many $e)
{
	print_r($e->get_err());
}
catch (Exception $e)
{
	echo $e->__toString();
}
?>