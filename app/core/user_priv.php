<?php
/**
 * Привилегии пользователей
 */
class ZN_User_Priv
{
	/**
	 * Добавить привилегию
	 * 
	 * @param int $admin_id
	 * @param int $group_id
	 */
	public static function add($admin_id, $group_id)
	{
		ZN_Admin::is_id($admin_id);
		ZN_User_Group::is_id($group_id);
		
		$data = 
		[
			"Admin_ID" => $admin_id,
			"Group_ID" => $group_id
		];
		Reg::db_core()->insert("user_priv", $data);
	}
	
	/**
	 * Удалить все привилегии
	 */
	public static function truncate()
	{
		$query = 
<<<SQL
TRUNCATE "user_priv"
SQL;
		Reg::db_core()->query($query, null, "user_priv");
	}
}
?>