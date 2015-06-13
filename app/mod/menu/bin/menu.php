<?php
/**
 * Меню
 */
class Menu
{
	/**
	 * Проверка по ID
	 * 
	 * @param int $id
	 */
	public static function is($id)
	{
		if (!Chf::uint($id))
		{
			throw new Exception("Номер у меню задан неверно. " . Chf::error());
		}

		$is = G::cache_db()->get("menu_is_" . $id);
		if ($is === null)
		{
			$is = (bool)G::db()->menu_is($id)->single();
			G::cache_db()->set("menu_is_" . $id, $is, "menu");
		}
		
		if ($is === false)
		{
			throw new Exception("Меню с номером «{$id}» не существует.");
		}
	}
	
	/**
	 * Добавить
	 * 
	 * @param string $name
	 * @return array
	 */
	public static function add($name)
	{
		/* Проверка */
		self::_check($name);

		/* Уникальность */
		self::_unique($name);

		/* SQL */
		$data = 
		[
			"Name" => $name
		];
		$id = G::db()->insert("menu", $data, "ID");

		/* Данные добавленного */
		return self::get($id);
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
		self::is($id);
		self::_check($name);

		/* Уникальность */
		self::_unique($name, $id);

		/* SQL */
		$data = 
		[
			"Name" => $name
		];
		G::db()->update("menu", $data, array("ID" => $id));

		/* Данные изменённого */
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

		G::db()->delete("menu", ["ID" => $id]);

		return $old;
	}

	/**
	 * Выборка по ID
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
	"Name"
FROM 
	"menu"
WHERE
	"ID" = $1
SQL;
		return G::db()->query($query, $id)->row();
	}

	/**
	 * Выборка всех
	 * 
	 * @return array
	 */
	public static function get_all()
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
		return G::db()->query($query)->assoc();
	}

	/**
	 * Проверка полей
	 * 
	 * @param string $name
	 */
	private static function _check($name)
	{
		Err::check_field($name, "string", false, "Name", "Наименование");
		
		Err::exception();
	}

	/**
	 * Уникальность
	 * 
	 * @param string $name
	 * @param int $id
	 */
	private static function _unique($name, $id = null)
	{
		$query = 
<<<SQL
SELECT 
	true
FROM 
	"menu"
WHERE 
	"Name" ILIKE $1 AND
	"ID" != $2
SQL;
		$rec = G::db()->query($query, [$name, (int)$id])->single();
		if ($rec !== null)
		{
			Err::add("Меню с полем «Наименование» : «{$name}» уже существует.", "Name");
		}
		
		Err::exception();
	}
}
?>