<?php
/**
 * Привилегии пользователей
 */
class _User_Group_Priv
{
	/**
	 * Добавить привилегию
	 * 
	 * @param int $admin_id
	 * @param int $group_id
	 */
	public static function add($admin_id, $group_id)
	{
//		_Admin::is($admin_id);
//		_User_Group::is($group_id);

		$data = 
		[
			"Admin_ID" => $admin_id,
			"Group_ID" => $group_id
		];
		G::db_core()->insert("user_group_priv", $data);
	}

	/**
	 * Удалить все привилегии
	 */
	public static function truncate()
	{
		$query = 
<<<SQL
TRUNCATE "user_group_priv"
SQL;
		G::db_core()->query($query);
	}
	
	/**
	 * Получить доступные видимые админки
	 * 
	 * @param int $group_id
	 * @param int $module_id
	 * @return array
	 */
	public static function get_admin_visible_and_allow($group_id, $module_id = null)
	{
		/* Если не root */
		$group_id = (int)$group_id;
		$sql_group = "";
		if ($group_id !== 0)
		{
			_User_Group::is($group_id);
			$sql_group = 
<<<SQL
	AND
	(
		"a"."ID" IN
		(
			SELECT 
				"gp"."Admin_ID"
			FROM
				"user_group_priv" as "gp"
			WHERE
				"gp"."Group_ID" = '{$group_id}'
		) 
		OR
		"a"."Allow_All" = true
	)				
SQL;
		}
		
		/* Админки */
		$query = 
<<<SQL
SELECT 
	"a"."ID",
	"a"."Name",
	"a"."Identified",
	"a"."Module_ID",
	"a"."Window"::int
FROM
	"admin" as "a"
WHERE
	"a"."Visible" = true 
	{$sql_group}
ORDER BY
	"a"."Sort" ASC
SQL;
		$admin = G::db_core()->query($query)->assoc();
		
		/* Модули */
		$module_id_ar = [];
		foreach ($admin as $val)
		{
			if (!in_array($val['Module_ID'], $module_id_ar))
			{
				$module_id_ar[] = $val['Module_ID'];
			}
		}
		$sql_module_id = "'" . implode("','", $module_id_ar) . "'";
		
		$query = 
<<<SQL
SELECT
	"ID",
	"Identified",
	"Name"
FROM
	"module"
WHERE
	"ID" IN ({$sql_module_id}) AND
	LEFT("Identified", 1) != '_' AND
	"Active" = true
ORDER BY
	"Identified" ASC 
SQL;
		$mod = G::db_core()->query($query)->assoc();
		
		$query = 
<<<SQL
SELECT
	"ID",
	"Identified",
	"Name"
FROM
	"module"
WHERE
	"ID" IN ({$sql_module_id}) AND
	LEFT("Identified", 1) = '_' AND
	"Active" = true
ORDER BY
	"Identified" ASC 
SQL;
		$smod = G::db_core()->query($query)->assoc();
		
		/* Общий массив */
		$module = array_merge($mod, $smod);
		foreach ($module as $m_key => $m_val)
		{
			$module[$m_key]['Admin'] = [];
			foreach ($admin as $a_val)
			{
				if ($m_val['ID'] === $a_val['Module_ID'])
				{
					$module[$m_key]['Admin'][] = $a_val;
				}
			}
		}
		
		/* Показать только по одному модулю */
		if ($module_id !== null)
		{
			foreach ($module as $val)
			{
				if ($val['ID'] === $module_id)
				{
					return $val;
				}
			}
		}
		else
		{
			return $module;
		}
	}
}

?>