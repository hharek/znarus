<?php
/**
 * Пункты меню
 */
class Menu_Item
{
	private static $_item_all;
	
	/**
	 * Добавить
	 * 
	 * @param string $name
	 * @param string $url
	 * @param int $parent
	 * @param int $menu_id
	 * @return array
	 */
	public static function add($name, $url, $parent, $menu_id)
	{
		/* Проверка */
		Err::check_field($name, "string", false, "Name", "Наименование");
		Err::check_field($url, "string", false, "Url", "Адрес");
		
		$parent = (int)$parent;
		if(!empty($parent))
		{self::is_id($parent);}
		else
		{$parent = null;}
		
		Menu::is_id($menu_id);
		
		Err::exception();
		
		/* Уникальность */
		self::_unique($name, $parent, $menu_id);
		Err::exception();
		
		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Url" => $url,
			"Parent" => $parent,
			"Menu_ID" => $menu_id
		];
		$id = Reg::db()->insert("menu_item", $data, "ID");
		
		/* Данные добавленного */
		return self::select_line_by_id($id);
	}
	
	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $name
	 * @param string $url
	 * @param int $parent
	 * @return array
	 */
	public static function edit($id, $name, $url, $parent)
	{
		/* Проверка */
		self::is_id($id);
		
		Err::check_field($name, "string", false, "Name", "Наименование");
		Err::check_field($url, "string", false, "Url", "Адрес");
		
		$parent = (int)$parent;
		if(!empty($parent))
		{self::is_id($parent);}
		else
		{$parent = null;}
		
		/* Уникальность */
		$item = self::select_line_by_id($id);
		self::_unique($name, $parent, $item['Menu_ID'], $id);
		Err::exception();
		
		/* SQL */
		$data =
		[
			"Name" => $name,
			"Url" => $url,
			"Parent" => $parent
		];
		Reg::db()->update("menu_item", $data, array("ID" => $id));
		
		/* Данные редактируемого */
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
		/* Данные по удаляемому пункту меню */
		$item = self::select_line_by_id($id);
		
		/* Подчинённые пункты меню */
		$item_child = self::select_list_by_parent($item['ID'], $item['Menu_ID']);
		foreach ($item_child as $val)
		{
			self::delete($val['ID']);
		}
		
		/* SQL */
		Reg::db()->delete("menu_item", array("ID" => $id));
		
		return $item;
	}
	
	/**
	 * Проверка по ID
	 * 
	 * @param int $id
	 */
	public static function is_id($id)
	{
		if(!Chf::uint($id))
		{throw new Exception_Admin("Номер у пункта меню задан неверно. ".Chf::error());}
		
		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"menu_item"
WHERE 
	"ID" = $1
SQL;
		$count = Reg::db()->query_one($query, $id, "menu_item");
		if($count < 1)
		{throw new Exception_Admin("Пункта меню с номером «{$id}» не существует.");}
	}
	
	/**
	 * Задать сортировку
	 * 
	 * @param int $id
	 * @param int $sort (up|down|::int)
	 */
	public static function sort($id, $sort)
	{
		$id = (int)$id;
		self::is_id($id);
		
		if(!in_array($sort, array('up','down')))
		{
			$sort = (int)$sort;
			
			$data =
			[
				"Sort" => $sort
			];
			Reg::db()->update("menu_item", $data, array("ID" => $id));
		}
		else 
		{
			$item = self::select_line_by_id($id);
			
			$query =
<<<SQL
SELECT 
	"ID", 
	"Sort"
FROM 
	"menu_item"
WHERE 
	COALESCE("Menu_ID", 0) = $1 AND
	COALESCE("Parent", 0) = $2
ORDER BY 
	"Sort" ASC
SQL;
			$other = Reg::db()->query_assoc($query, [$item['Menu_ID'], $item['Parent']], "menu_item");
			
			if(count($other) < 2)
			{
				throw new Exception_Admin("Необходимо хотя бы два исполнителя.");
			}

			foreach ($other as $key=>$val)
			{
				if($val['ID'] == $id)
				{break;}
			}

			if($sort == "up")
			{
				if($key == 0)
				{throw new Exception_Admin("Выше некуда.");}
				
				$id_next = $other[$key-1]['ID'];
				$sort_int = $other[$key-1]['Sort'];
				$sort_int_next = $other[$key]['Sort'];
			}
			elseif($sort == "down")
			{
				if($key == count($other)-1)
				{throw new Exception_Admin("Ниже некуда.");}
		
				$id_next = $other[$key+1]['ID'];
				$sort_int = $other[$key+1]['Sort'];
				$sort_int_next = $other[$key]['Sort'];
			}

			$data =
			[
				"Sort" => $sort_int
			];
			Reg::db()->update("menu_item", $data, array("ID" => $id));
		
			$data =
			[
				"Sort" => $sort_int_next
			];
			Reg::db()->update("menu_item", $data, array("ID" => $id_next));
		}
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
	"Name",
	"Url",
	COALESCE("Parent", 0) as "Parent",
	COALESCE("Menu_ID", 0) as "Menu_ID",
	"Sort"
FROM 
	"menu_item"
WHERE
	"ID" = $1
SQL;
		$item = Reg::db()->query_line($query, $id, "menu_item");
		
		return $item;
	}
	
	/**
	 * Выборка по корню
	 * 
	 * @param int $parent
	 * @param int $menu_id
	 * @return array
	 */
	public static function select_list_by_parent($parent, $menu_id)
	{
		$parent = (int)$parent;
		if($parent !== 0)
		{self::is_id($parent);}
		else 
		{$parent = 0;}
		
		Menu::is_id($menu_id);
		
		$query =
<<<SQL
SELECT 
	"ID",
	"Name",
	"Url"
FROM 
	"menu_item"
WHERE
	COALESCE("Menu_ID", 0) = $1 AND
	COALESCE("Parent", 0) = $2
ORDER BY 
	"Sort" ASC
SQL;
		$item = Reg::db()->query_assoc($query, [$menu_id, $parent], "menu_item");
		
		return $item;
	}
	
	/**
	 * Выборка всех с подчинёнными
	 * 
	 * @param int $parent
	 * @param int $menu_id
	 * @param int $current
	 * @return array
	 */
	public static function select_list_child_by_parent($menu_id, $parent, $current=0)
	{
		/* Проверка */
		$menu_id = (int)$menu_id;
		$parent = (int)$parent;
		$current = (int)$current;
		
		/* Все пункты меню */
		if(empty(self::$_item_all))
		{
			$query =
<<<SQL
SELECT 
	"ID",
	"Name",
	"Url",
	COALESCE("Parent", 0) as "Parent",
	COALESCE("Menu_ID", 0) as "Menu_ID"
FROM 
	"menu_item"
ORDER BY
	"Sort" ASC
SQL;
			self::$_item_all = Reg::db()->query_assoc($query, null, "menu_item");
		}
		
		/* Перебор */
		$child = array();
		foreach (self::$_item_all as $key=>$val)
		{
			if($val['ID'] == $current)
			{continue;}
			
			if((int)$val['Menu_ID'] === $menu_id and (int)$val['Parent'] === $parent)
			{
				$child[] = 
				[
					'ID' => $val['ID'],
					'Name' => $val['Name'],
					'Url' => $val['Url'],
					'Child' => self::select_list_child_by_parent($menu_id, $val['ID'], $current)
				];
				
				unset(self::$_item_all[$key]);
			}
		}

		return $child;
	}
	
	/**
	 * Уникальность
	 * 
	 * @param string $name
	 * @param int $parent
	 * @param int $menu_id
	 * @param int $id
	 */
	private static function _unique($name, $parent, $menu_id, $id=null)
	{
		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"menu_item"
WHERE 
	"Name" = $1 AND
	COALESCE("Parent", 0) = $2 AND
	"Menu_ID" = $3
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		$count = Reg::db()->query_one($query, [$name, $parent, $menu_id], "menu_item");
		if($count > 0)
		{Err::add("Пункт меню с полем «Наименование» : «{$name}» уже существует.", "Name");}
	}
}
?>