<?php
/**
 * Группы пользователей
 */
class ZN_User_Group
{
	/**
	 * Добавить
	 * 
	 * @param string $name
	 * @return array
	 */
	public static function add($name)
	{
		/* Проверка */
		Err::check_field($name, "string", false, "Name", "Наименование");
		Err::exception();
		
		/* Уникальность */
		self::_unique($name);
		Err::exception();
		
		/* SQL */
		$data = 
		[
			"Name" => $name
		];
		$id = Reg::db_core()->insert("user_group", $data, "ID");
		
		/* Данные добавленного */
		return self::select_line_by_id($id);
	}
	
	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $name
	 * @return array
	 */
	public static function edit($id, $name)
	{
		/* Проверка */
		self::is_id($id);
		Err::check_field($name, "string", false, "Name", "Наименование");
		Err::exception();
		
		/* Уникальность */
		self::_unique($name, $id);
		Err::exception();
		
		/* SQL */
		$data =
		[
			"Name" => $name
		];
		Reg::db_core()->update("user_group", $data, array("ID" => $id));
		
		/* Данные изменённого */
		return self::select_line_by_id($id);
	}
	
	/**
	 * Удалить
	 * 
	 * @param int $id
	 * @return array
	 */
	public static function delete($id)
	{
		/* Проверка */
		$group = self::select_line_by_id($id);
		
		/* Удаление пользователей */
		$user = ZN_User::select_list_by_group_id($id);
		foreach ($user as $val)
		{ZN_User::delete($val['ID']);}
		
		/* Удалить привилегии */
		Reg::db_core()->delete("user_priv", array("Group_ID" => $id));
		
		/* Удалить */
		Reg::db_core()->delete("user_group", array("ID" => $id));
		
		/* Данные удалённого */
		return $group;
	}
	
	/**
	 * Проверка по ID
	 * 
	 * @param int $id
	 */
	public static function is_id($id)
	{
		if(!Chf::uint($id))
		{throw new Exception_Constr("Номер у группы задан неверно. ".Chf::error());}
		
		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"user_group"
WHERE 
	"ID" = $1
SQL;
		$count = Reg::db_core()->query_one($query, $id, "user_group");
		if($count < 1)
		{throw new Exception_Constr("Группы с номером «{$id}» не существует.");}
	}
	
	/**
	 * Выборка строки по ID
	 * 
	 * @param int $id
	 * @return array
	 */
	public static function select_line_by_id($id)
	{
		self::is_id($id);
		
		$query = 
<<<SQL
SELECT
	"ID",
	"Name"
FROM 
	"user_group"
WHERE 
	"ID" = $1
SQL;
		$group = Reg::db_core()->query_line($query, $id, "user_group");
		
		return $group;
	}
	
	/**
	 * Выборка всех
	 * 
	 * @return array
	 */
	public static function select_list()
	{
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
		
		return $group;
	}
	
	/**
	 * Уникальность
	 * 
	 * @param string $name
	 * @param int $id
	 */
	public static function _unique($name, $id=null)
	{
		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"user_group"
WHERE 
	"Name" = $1
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		$count = Reg::db_core()->query_one($query, $name, "user_group");
		if($count > 0)
		{Err::add("Группа с полем «Наименование» : «{$name}» уже существует.", "Name");}
	}
}
?>