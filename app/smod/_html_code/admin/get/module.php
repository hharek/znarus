<?php
$module = _Module::get_by_type("all");

$query =
<<<SQL
SELECT 
	"ID", 
	"Identified", 
	"Name", 
	"Module_ID"
FROM 
	"inc"
ORDER BY
	"Identified" ASC 
SQL;
$inc = G::db_core()->query($query)->assoc();

$query =
<<<SQL
SELECT 
	"ID", 
	"Identified", 
	"Name", 
	"Module_ID"
FROM 
	"exe"
ORDER BY
	"Identified" ASC 
SQL;
$exe = G::db_core()->query($query)->assoc();

/* Общий массив */
foreach ($module as $m_key=>$m_val)
{
	$module[$m_key]["Inc"] = array();
	foreach ($inc as $i_key=>$i_val)
	{
		if($m_val['ID'] === $i_val['Module_ID'])
		{
			$module[$m_key]["Inc"][] = $i_val;
		}
	}
	
	$module[$m_key]["Exe"] = array();
	foreach ($exe as $e_key=>$e_val)
	{
		if($m_val['ID'] === $e_val['Module_ID'])
		{
			$module[$m_key]["Exe"][] = $e_val;
		}
	}
	
	if(empty($module[$m_key]["Inc"]) and empty($module[$m_key]["Exe"]))
	{
		unset($module[$m_key]);
	}
}

title("Модули");

?>