<?php
/**
 * Пункты меню
 */
class Menu_Item
{
	/**
	 * Все пункты меню
	 * 
	 * @var array
	 */
	private static $_item_all;
	
	/**
	 * Проверка по ID
	 * 
	 * @param int $id
	 */
	public static function is($id)
	{
		if (!Chf::uint($id))
		{
			throw new Exception("Номер у пункта меню задан неверно. " . Chf::error());
		}

		$is = G::cache_db()->get("menu_item_is_" . $id);
		if ($is === null)
		{
			$is = (bool)G::db()->menu_item_is($id)->single();
			G::cache_db()->set("menu_item_is_" . $id, $is, "menu_item");
		}
		
		if ($is === false)
		{
			throw new Exception("Пункта меню с номером «{$id}» не существует.");
		}
	}
	
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
		self::_check($name, $url, $parent);
		Menu::is($menu_id);

		/* Уникальность */
		self::_unique($name, $parent, $menu_id);

		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Url" => $url,
			"Parent" => $parent,
			"Menu_ID" => $menu_id
		];
		$id = G::db()->insert("menu_item", $data, "ID");
		
		/* Удалить кэш */
		G::cache_db()->delete_tag("menu");

		/* Данные добавленного */
		return self::get($id);
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
		self::is($id);
		self::_check($name, $url, $parent);

		/* Уникальность */
		$old = self::get($id);
		self::_unique($name, $parent, $old['Menu_ID'], $id);

		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Url" => $url,
			"Parent" => $parent
		];
		G::db()->update("menu_item", $data, ["ID" => $id]);
		
		/* Удалить кэш */
		G::cache_db()->delete_tag("menu");

		/* Данные редактируемого */
		return self::get($id);
	}

	/**
	 * Удалить
	 * 
	 * @param int $id
	 * @return array
	 */
	public static function delete($id)
	{
		$old = self::get($id);

		G::db()->delete("menu_item", ["ID" => $id]);
		
		/* Удалить кэш */
		G::cache_db()->delete_tag("menu");

		return $old;
	}

	/**
	 * Задать сортировку
	 * 
	 * @param int $id
	 * @param int $sort (up|down|::int)
	 */
	public static function sort($id, $sort)
	{
		$id = (int) $id;
		self::is($id);

		if (!in_array($sort, ['up', 'down']))
		{
			$sort = (int) $sort;

			$data = 
			[
				"Sort" => $sort
			];
			G::db()->update("menu_item", $data, ["ID" => $id]);
		}
		else
		{
			$item = self::get($id);

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
			$other = G::db()->query($query, [$item['Menu_ID'], $item['Parent']])->assoc();

			if (count($other) < 2)
			{
				throw new Exception("Необходимо хотя бы два пункта меню.");
			}

			foreach ($other as $key => $val)
			{
				if ($val['ID'] == $id)
				{
					break;
				}
			}

			if ($sort == "up")
			{
				if ($key == 0)
				{
					throw new Exception("Выше некуда.");
				}

				$id_next = $other[$key - 1]['ID'];
				$sort_int = $other[$key - 1]['Sort'];
				$sort_int_next = $other[$key]['Sort'];
			}
			elseif ($sort == "down")
			{
				if ($key == count($other) - 1)
				{
					throw new Exception("Ниже некуда.");
				}

				$id_next = $other[$key + 1]['ID'];
				$sort_int = $other[$key + 1]['Sort'];
				$sort_int_next = $other[$key]['Sort'];
			}

			$data = 
			[
				"Sort" => $sort_int
			];
			G::db()->update("menu_item", $data, ["ID" => $id]);

			$data = 
			[
				"Sort" => $sort_int_next
			];
			G::db()->update("menu_item", $data, ["ID" => $id_next]);
			
			/* Удалить кэш */
			G::cache_db()->delete_tag("menu");
		}
	}

	/**
	 * Выборка строки по ID
	 * 
	 * @param int $id
	 * @return array
	 */
	public static function get($id)
	{
		self::is($id);

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
		return G::db()->query($query, $id)->row();
	}

	/**
	 * Выборка по корню
	 * 
	 * @param int $menu_id
	 * @param int $parent
	 * @return array
	 */
	public static function get_by_parent($menu_id, $parent)
	{
		/* Проверка */
		Menu::is($menu_id);
		$parent = (int) $parent;
		if ($parent !== 0)
		{
			self::is($parent);
		}
		
		/* Пункты меню */
		$item = G::cache_db()->get("menu_item_by_parent_" . $menu_id . "_" . $parent);
		if ($item === null)
		{
			$item = G::db()->menu_item_by_parent($menu_id, $parent)->assoc();
			G::cache_db()->set("menu_item_by_parent_" . $menu_id . "_" . $parent, $item, "menu_item");
		}
		
		return $item;
	}

	/**
	 * Выборка всех с подчинёнными
	 * 
	 * @param int $menu_id
	 * @param int $parent
	 * @param int $current
	 * @return array
	 */
	public static function get_child_by_parent($menu_id, $parent, $current = 0)
	{
		/* Проверка */
		$menu_id = (int) $menu_id;
		$parent = (int) $parent;
		$current = (int) $current;

		/* Все пункты меню */
		if (empty(self::$_item_all))
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
			self::$_item_all = G::db()->query($query)->assoc();
		}

		/* Перебор */
		$child = [];
		foreach (self::$_item_all as $key => $val)
		{
			if ((int)$val['ID'] === $current)
			{
				continue;
			}

			if ((int) $val['Menu_ID'] === $menu_id and (int) $val['Parent'] === $parent)
			{
				$child[] = 
				[
					'ID' => $val['ID'],
					'Name' => $val['Name'],
					'Url' => $val['Url'],
					'Child' => self::get_child_by_parent($menu_id, $val['ID'], $current)
				];

				unset(self::$_item_all[$key]);
			}
		}

		return $child;
	}

	/**
	 * Проверка полей
	 * 
	 * @param string $name
	 * @param string $url
	 * @param int $parent
	 */
	private static function _check($name, &$url, &$parent)
	{
		Err::check_field($name, "string", false, "Name", "Наименование");
		
		Err::check_field($url, "string", false, "Url", "Адрес");
		$url = mb_strtolower($url);

		$parent = (int) $parent;
		if ($parent !== 0)
		{
			self::is($parent);
		}
		else
		{
			$parent = null;
		}

		Err::exception();
	}

	/**
	 * Уникальность
	 * 
	 * @param string $name
	 * @param int $parent
	 * @param int $menu_id
	 * @param int $id
	 */
	private static function _unique($name, $parent, $menu_id, $id = null)
	{
		$query = 
<<<SQL
SELECT 
	true
FROM 
	"menu_item"
WHERE 
	"Name" ILIKE $1 AND
	COALESCE("Parent", 0) = $2 AND
	"Menu_ID" = $3 AND
	"ID" != $4
SQL;
		$rec = G::db()->query($query, [$name, (int)$parent, $menu_id, (int)$id])->single();
		if ($rec !== null)
		{
			Err::add("Пункт меню с полем «Наименование» : «{$name}» уже существует.", "Name");
		}
		
		Err::exception();
	}
}
?>