<?php
$robots = ""; $is_robots = false;
if(G::file()->is_file("robots.txt"))
{
	$robots = G::file()->get("robots.txt");
	$is_robots = true;
}

title("robots.txt");
path(["robots.txt"])
?>