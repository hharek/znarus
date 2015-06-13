<?php
/* Проверка на наличие админки */
$query = 
<<<SQL
SELECT 
	"a"."ID",
	"a"."Identified",
	"a"."Window"::int,
	"a"."Allow_All"::int,
	"a"."Module_ID"
FROM 
	"admin" as "a",
	"module" as "m"
WHERE 
	"a"."Identified" = $1 AND
	"a"."Module_ID" = "m"."ID" AND
	"m"."Identified" = $2
SQL;
$_admin = G::db_core()->query($query, [$_act, $_mod])->row();
if (empty($_admin))
{
	throw new Exception("Админки «" . $_mod . "/" . $_act . "» не существует.");
}

/* Проверка на наличие привилегий */
if (G::user()['Group_Name'] !== "root" and $_admin['Allow_All'] !== "1")
{
	$query = 
<<<SQL
SELECT 
	true
FROM 
	"user_group_priv"
WHERE 
	"Admin_ID" = $1 AND
	"Group_ID" = $2
SQL;
	$rec = G::db_core()->query($query, [$_admin['ID'], G::user()['Group_ID']])->single();
	if ($rec === null)
	{
		throw new Exception("Доступ запрещён.");
	}
}

/* Наименование модуля и достуные админки */
$_data['module_admin'] = _User_Group_Priv::get_admin_visible_and_allow(G::user()['Group_ID'], $_admin['Module_ID']);
?>