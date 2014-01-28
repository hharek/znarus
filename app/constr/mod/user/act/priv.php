<?php
/* Модули */
$query =
<<<SQL
SELECT 
	"ID", 
	"Name"
FROM 
	"module" 
WHERE 
	"ID" IN
	(
		SELECT DISTINCT "Module_ID"
		FROM "admin"
	)
ORDER BY 
	"Name" ASC
SQL;
$module = Reg::db_core()->query_assoc($query, null, array("module","admin"));

/* Админки */
$query =
<<<SQL
SELECT 
	"ID", 
	"Name",
	"Module_ID",
	"Visible"::int,
	"Allow_All"::int
FROM 
	"admin" 
ORDER BY 
	"Sort" ASC
SQL;
$admin = Reg::db_core()->query_assoc($query, null, "admin");

/* Группы */
$query =
<<<SQL
SELECT 
	"ID", 
	"Name"
FROM 
	"user_group"
ORDER BY 
	"Name" ASC
SQL;
$group = Reg::db_core()->query_assoc($query, null, "user_group");
foreach ($group as $g_key=>$g_val)
{
	$name = "";
	for ($i=0; $i<mb_strlen($g_val['Name']); $i++)
	{
		$name .= mb_substr($g_val['Name'], $i, 1)."<br/>";
	}
	$group[$g_key]['vertical'] = $name;
}

/* Привилегии */
$query =
<<<SQL
SELECT 
	"Admin_ID", 
	"Group_ID"
FROM 
	"user_priv"
SQL;
$priv = Reg::db_core()->query_assoc($query, null, "user_priv");


/* Формирование */
foreach ($module as $m_key=>$m_val)
{
	/* Группы */
	$module[$m_key]['group'] = array();
	foreach ($group as $mg_val)
	{
		$module[$m_key]['group'][] = 
		[
			'ID' => $mg_val['ID'],
			'Name' => $mg_val['Name'],
			'count' => 0
		];
	}
	
	/* Админки */
	$module[$m_key]['admin'] = array();
	foreach ($admin as $a_key=>$a_val)
	{
		if($m_val['ID'] === $a_val['Module_ID'])
		{
			/* Привилегии */
			$a_val['group_priv'] = array();
			foreach ($group as $g_val)
			{
				$priv_isset = false;
				foreach ($priv as $p_key=>$p_val)
				{
					if($a_val['ID'] === $p_val['Admin_ID'] and $g_val['ID'] === $p_val['Group_ID'])
					{
						/* Добавить привилегию */
						$priv_isset = true;
						
						/* Добавить счётчик к группе */
						foreach ($module[$m_key]['group'] as $mg_key=>$mg_val)
						{
							if($g_val['ID'] === $mg_val['ID'])
							{
								$module[$m_key]['group'][$mg_key]['count']++;
							}
						}
						
						/* Удалить из массива */
						unset($priv[$p_key]);
						break;
					}
				}
				
				$a_val['group_priv'][] = 
				[
					'group_id' => $g_val['ID'],
					'priv' => $priv_isset
				];
			}
			
			$module[$m_key]['admin'][] = $a_val;
			unset($admin[$a_key]);
		}
	}
}
?>