<?php
/**
 * Группы пользователей
 */
class _User_Group
{
	/**
	 * Проверка по ID
	 * 
	 * @param int $id
	 */
	public static function is($id)
	{
		if (!Type::check("uint", $id))
		{
			throw new Exception("Номер у группы задан неверно. " . Type::get_last_error());
		}

		$query = 
<<<SQL
SELECT 
	true
FROM 
	"user_group"
WHERE 
	"ID" = $1
SQL;
		$rec = G::db_core()->query($query, $id)->single();
		if ($rec === null)
		{
			throw new Exception("Группы с номером «{$id}» не существует.");
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
		$id = G::db_core()->insert("user_group", $data, "ID");

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
		G::db_core()->update("user_group", $data, ["ID" => $id]);

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
		/* Проверка */
		$old = self::get($id);

		/* SQL */
		G::db_core()->delete("user_group", ["ID" => $id]);

		/* Данные удалённого */
		return $old;
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
	"Name"
FROM 
	"user_group"
WHERE 
	"ID" = $1
SQL;
		return G::db_core()->query($query, $id)->row();
	}

	/**
	 * Все группы
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
	"user_group"
ORDER BY
	"Name" ASC
SQL;
		return G::db_core()->query($query)->assoc();
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
	"user_group"
WHERE 
	"Name" = $1 AND
	"ID" != $2
SQL;
		$rec = G::db_core()->query($query, [$name, (int)$id])->single();
		if ($rec !== null)
		{
			Err::add("Группа с полем «Наименование» : «{$name}» уже существует.", "Name");
		}
		
		Err::exception();
	}
}
?>