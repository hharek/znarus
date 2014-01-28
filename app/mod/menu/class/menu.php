<?php
/**
 * Меню
 */
class Menu
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
		$id = Reg::db()->insert("menu", $data, "ID");
		
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
		Reg::db()->update("menu", $data, array("ID" => $id));
		
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
		/* Меню */
		$menu = self::select_line_by_id($id);
		
		/* Удаление пунктов меню */
		$item = Menu_Item::select_list_by_parent(0, $id);
		foreach ($item as $val)
		{Menu_Item::delete($val['ID']);}
		
		/* SQL */
		Reg::db()->delete("menu", array("ID" => $id));
		
		return $menu;
	}
	
	/**
	 * Проверка по ID
	 * 
	 * @param int $id
	 */
	public static function is_id($id)
	{
		if(!Chf::uint($id))
		{throw new Exception_Admin("Номер у меню задан неверно. ".Chf::error());}
		
		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"menu"
WHERE 
	"ID" = $1
SQL;
		$count = Reg::db()->query_one($query, $id, "menu");
		if($count < 1)
		{throw new Exception_Admin("Меню с номером «{$id}» не существует.");}
	}
	
	/**
	 * Выборка по ID
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
	"menu"
WHERE
	"ID" = $1
SQL;
		$menu = Reg::db()->query_line($query, $id, "menu");
		
		return $menu;
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
	"menu"
ORDER BY
	"Name" ASC
SQL;
		$menu = Reg::db()->query_assoc($query, null, "menu");
		
		return $menu;
	}
	
	/**
	 * Уникальность
	 * 
	 * @param string $name
	 * @param int $id
	 */
	private static function _unique($name, $id=null)
	{
		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"menu"
WHERE 
	"Name" = $1
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		$count = Reg::db()->query_one($query, $name, "menu");
		if($count > 0)
		{Err::add("Меню с полем «Наименование» : «{$name}» уже существует.", "Name");}
	}
}
?>