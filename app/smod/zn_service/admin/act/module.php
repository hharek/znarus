<?php
title("Модули");
path(["Список модулей [#zn_sevice/module]"]);

$user = ZN_User_Action::data();

/* Если не root */
if($user['Email'] !== "root")
{
	$query =
<<<SQL
SELECT DISTINCT
	"m"."ID",
	"m"."Name",
	"m"."Identified",
	"m"."Desc"
FROM 
	"user_priv" as "up", 
	"admin" as "a",
	"module" as "m"
WHERE 
	"up"."Group_ID" = $1 AND 
	"up"."Admin_ID" = "a"."ID" AND
	"a"."Module_ID" = "m"."ID"
ORDER BY 
	"m"."Identified" ASC
SQL;
	$module = Reg::db_core()->query_assoc($query, $user['Group_ID'], ['user_priv','admin','module']);
	
	$query =
<<<SQL
SELECT 
	"a"."ID",
	"a"."Name",
	"a"."Identified",
	"a"."Module_ID"
FROM 
	"user_priv" as "up", 
	"admin" as "a"
WHERE 
	"up"."Group_ID" = $1 AND 
	"up"."Admin_ID" = "a"."ID"
ORDER BY 
	"a"."Sort" ASC
SQL;
	$admin = Reg::db_core()->query_assoc($query, $user['Group_ID'], ['user_priv','admin']);
}
/* Если root */
else
{
	$module = ZN_Module::select_list();
	$query =
<<<SQL
SELECT 
	"ID", 
	"Name", 
	"Identified", 
	"Module_ID"
FROM 
	"admin"
ORDER BY 
	"Sort" ASC
SQL;
	$admin = Reg::db_core()->query_assoc($query, null, "admin");
}

foreach ($module as $m_key => $m_val)
{
	$module[$m_key]['admin'] = array();
	foreach ($admin as $a_key=>$a_val)
	{
		if($m_val['ID'] === $a_val['Module_ID'])
		{
			$module[$m_key]['admin'][] = $a_val;
			unset($admin[$a_key]);
		}
	}
}
?>