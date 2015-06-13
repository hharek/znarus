<?php
/**
 * Параметры
 */
class _Param
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
			throw new Exception("Номер у параметра задан неверно. " . Chf::error());
		}

		$query = 
<<<SQL
SELECT 
	true
FROM 
	"param"
WHERE 
	"ID" = $1
SQL;
		$rec = G::db_core()->query($query, $id)->single();
		if ($rec === null)
		{
			throw new Exception("Параметра с номером «{$id}» не существует.");
		}
	}

	/**
	 * Добавить
	 * 
	 * @param string $name
	 * @param string $identified
	 * @param string $type (string|int|bool)
	 * @param string $value
	 * @param int $module_id
	 * @return array
	 */
	public static function add($name, $identified, $type, $value, $module_id)
	{
		/* Проверка */
		self::_check($name, $identified, $type, $value);

		$module_id = (int) $module_id;
		if ($module_id !== 0)
		{
			_Module::is($module_id);
		}
		else
		{
			$module_id = null;
		}
		Err::exception();

		/* Уникальность */
		self::_unique($name, $identified, $module_id);

		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Identified" => $identified,
			"Type" => $type,
			"Value" => $value,
			"Module_ID" => $module_id
		];
		$id = G::db_core()->insert("param", $data, "ID");
		
		/* Удалить кэш */
		G::cache_db_core()->delete_tag("param");

		/* Данные добавленного */
		return self::get($id);
	}

	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $name
	 * @param string $identified
	 * @param string $type (string|int|bool)
	 * @param string $value
	 * @return array
	 */
	public static function edit($id, $name, $identified, $type, $value)
	{
		/* Проверка */
		self::is($id);
		self::_check($name, $identified, $type, $value);
		
		/* Уникальность */
		$old = self::get($id);
		self::_unique($name, $identified, $old['Module_ID'], $id);

		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Identified" => $identified,
			"Type" => $type,
			"Value" => $value
		];
		G::db_core()->update("param", $data, ["ID" => $id]);
		
		/* Удалить кэш */
		G::cache_db_core()->delete_tag("param");

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

		/* Удалить */
		G::db_core()->delete("param", ["ID" => $id]);
		
		/* Удалить кэш */
		G::cache_db_core()->delete_tag("param");

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
	"Name",
	"Identified",
	"Type",
	"Value",
	"Module_ID"
FROM 
	"param"
WHERE 
	"ID" = $1
SQL;
		return G::db_core()->query($query, $id)->row();
	}

	/**
	 * Выборка всех по модулю
	 * 
	 * @param int $module_id
	 * @return array
	 */
	public static function get_by_module($module_id)
	{
		_Module::is($module_id);

		$query = 
<<<SQL
SELECT
	"ID",
	"Name",
	"Identified",
	"Type",
	"Value",
	"Module_ID"
FROM 
	"param"
WHERE 
	"Module_ID" = $1
ORDER BY
	"Identified" ASC
SQL;
		return G::db_core()->query($query, $module_id)->assoc();
	}
	
	/**
	 * Получить все системные параметры
	 * 
	 * @return array
	 */
	public static function get_sys_all()
	{
		$query =
<<<SQL
SELECT 
	"ID", 
	"Name", 
	"Identified", 
	"Type", 
	"Value"
FROM 
	"param"
WHERE 
	"Module_ID" IS NULL
ORDER BY 
	"Identified" ASC
SQL;
		return G::db_core()->query($query)->assoc();
	}

	/**
	 * Проверка полей
	 * 
	 * @param string $name
	 * @param string $identified
	 * @param enum $type (string|int|bool)
	 * @param string $value
	 */
	private static function _check($name, $identified, $type, $value)
	{
		Err::check_field($name, "string", false, "Name", "Наименование");
		Err::check_field($identified, "identified", false, "Identified", "Идентификатор");

		if (!in_array($type, ["string", "int", "bool"]))
		{
			Err::add("Поле «Тип» задано неверно. Допустимые значения: string ,int, bool.", "Type");
		}

		Err::check_field($value, $type, true, "Value", "Значение");
		
		Err::exception();
	}

	/**
	 * Уникальность
	 * 
	 * @param string $name
	 * @param string $identified
	 * @param int $module_id
	 * @param int $id
	 */
	private static function _unique($name, $identified, $module_id, $id = null)
	{
		$query = 
<<<SQL
SELECT 
	true
FROM 
	"param"
WHERE 
	"Name" ILIKE $1 AND 
	COALESCE ("Module_ID", 0) = $2 AND
	"ID" != $3
SQL;
		$rec = G::db_core()->query($query, [$name, (int)$module_id, (int)$id])->single();
		if ($rec !== null)
		{
			Err::add("Параметр с полем «Наименование» : «{$name}» уже существует.", "Name");
		}

		$query = 
<<<SQL
SELECT 
	true
FROM 
	"param"
WHERE 
	"Identified" = $1 AND
	COALESCE ("Module_ID", 0) = $2 AND
	"ID" != $3
SQL;
		$rec = G::db_core()->query($query, [$identified, (int)$module_id, (int)$id])->single();
		if ($rec !== null)
		{
			Err::add("Параметр с полем «Идентификатор» : «{$identified}» уже существует.", "Identified");
		}
		
		Err::exception();
	}
}
?>