<?php
if(isset($_GET['token']) and $_GET['token'] === $_COOKIE['token'])
{
	setcookie("sid", "", time()-3600, "/" . urlencode(Reg::url_constr()));
}

header("Location: /" . Reg::url_constr());
?>