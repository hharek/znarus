<?php
ZN_User_Priv::truncate();

foreach ($_POST as $key=>$val)
{
	if(mb_substr($key, 0, 5) === "priv_")
	{
		$ar = explode("_", $key);
		$admin_id = (int)$ar[1];
		$group_id = (int)$ar[2];
		
		ZN_User_Priv::add($admin_id, $group_id);
	}
}

mess_ok("Новые привилегии назначены.");
?>