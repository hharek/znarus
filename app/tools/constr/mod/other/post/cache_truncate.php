<?php
if ($_POST['db_core'] === "1")
{
	G::cache_db_core()->truncate();
}

if ($_POST['db'] === "1")
{
	G::cache_db()->truncate();
}

if ($_POST['route'] === "1")
{
	G::cache_route()->truncate();
}

if ($_POST['page'] === "1")
{
	G::cache_page()->truncate();
}

mess_ok("Кэш очищен.");
?>