<?php
/**
 * Пункты меню
 */
class Menu_Item extends TM
{
	protected static $_name = "Пункты меню";
	protected static $_table = "menu_item";
	protected static $_field = 
	[
		[
			"identified" => "ID",
			"name" => "Порядковый номер",
			"type" => "id"
		],
		[
			"identified" => "Name",
			"name" => "Наименование",
			"type" => "string",
			"unique" => true,
			"unique_key" => "UN_Name",
			"check" => false
		],
		[
			"identified" => "Url",
			"name" => "Урл",
			"type" => "url",
			"check" => false
		],
		[
			"identified" => "Parent",
			"name" => "Корень",
			"type" => "int",
			"foreign" => 
			[
				"class" => "Menu_Item",
				"table" => "menu_item",
				"field" => "ID"
			],
			"unique" => true,
			"unique_key" => "UN_Name",
			"default" => null,
			"null" => true
		],
		[
			"identified" => "Menu_ID",
			"name" => "Привязка к меню",
			"type" => "int",
			"foreign" => 
			[
				"class" => "Menu",
				"table" => "menu",
				"field" => "ID"
			],
			"unique" => true,
			"unique_key" => "UN_Name"
		],
		[
			"identified" => "Order",
			"name" => "Сортировка",
			"type" => "order",
			"order_where" => ["Parent"]
		],
		[
			"identified" => "Icon",
			"name" => "Иконка",
			"type" => "string"
		],
		[
			"identified" => "Active",
			"name" => "Активность",
			"type" => "bool",
			"default" => true,
			"require" => false
		]
	];
	
	/**
	 * Все пункты меню
	 * 
	 * @var array
	 */
	private static $_item_all;
	
	/**
	 * Добавить
	 * 
	 * @param array $data
	 * @return array
	 */
	public static function add ($data) : array
	{
		/* Удалить кэш */
		Cache::delete(["module" => "menu"]);
		
		return static::insert($data);
	}
	
	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param array $data
	 * @return array
	 */
	public static function edit (int $id, array $data) : array
	{
		/* Удалить кэш */
		Cache::delete(["module" => "menu"]);
		
		return static::update($data, $id);
	}
	
	/**
	 * Удалить
	 * 
	 * @param int $id
	 * @return array
	 */
	public static function remove (int $id) : array
	{
		/* Удалить кэш */
		Cache::delete(["module" => "menu"]);
		
		return static::delete($id);
	}
	
	/**
	 * Выборка всех с подчинёнными
	 * 
	 * @param int $menu_id
	 * @param int $parent
	 * @param int $current
	 * @param boolean $only_active
	 * @return array
	 */
	public static function get_child_by_parent($menu_id, $parent, $current = 0, $only_active = false)
	{
		/* Проверка */
		$menu_id = (int) $menu_id;
		$parent = (int) $parent;
		$current = (int) $current;
		$only_active = (int)(bool)$only_active;

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
	COALESCE("Menu_ID", 0) as "Menu_ID",
	"Active"::int
FROM 
	"menu_item"
WHERE
	(
		$1 = 1 AND
		"Active" = true
	) OR
	$1 = 0
ORDER BY
	"Order" ASC
SQL;
			self::$_item_all = G::db()->query($query, $only_active)->assoc();
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
					'Active' => $val['Active'],
					'Parent' => $val['Parent'],
					'Child' => self::get_child_by_parent($menu_id, $val['ID'], $current)
				];

//				unset(self::$_item_all[$key]);
			}
		}

		return $child;
	}
}
?>