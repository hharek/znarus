<?php
$robots = ""; $is_robots = false;
if(Reg::file()->is_file("robots.txt"))
{
	$robots = Reg::file()->get("robots.txt");
	$is_robots = true;
}
?>