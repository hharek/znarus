<?php
/**
 * Тексты
 */
class _Text
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
			throw new Exception("Номер у текста задан неверно. " . Chf::error());
		}

		$query = 
<<<SQL
SELECT 
	true
FROM 
	"text"
WHERE 
	"ID" = $1
SQL;
		$rec = G::db_core()->query($query, $id)->single();
		if ($rec === null)
		{
			throw new Exception("Текста с номером «{$id}» не существует.");
		}
	}
	
	/**
	 * Добавить
	 * 
	 * @param string $name
	 * @param string $identified
	 * @param string $value
	 * @param int $module_id
	 * @return array
	 */
	public static function add($name, $identified, $value, $module_id)
	{
		/* Проверка */
		self::_check($name, $identified, $value);
		$module_id = (int) $module_id;
		if ($module_id !== 0)
		{
			_Module::is($module_id);
		}
		else
		{
			$module_id = null;
		}

		/* Уникальность */
		self::_unique($name, $identified, $module_id);

		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Identified" => $identified,
			"Value" => $value,
			"Module_ID" => $module_id
		];
		$id = G::db_core()->insert("text", $data, "ID");
		
		/* Удалить кэш */
		G::cache_db_core()->delete_tag("text");

		/* Данные добавленного */
		return self::get($id);
	}

	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $name
	 * @param string $identified
	 * @param string $value
	 * @return array
	 */
	public static function edit($id, $name, $identified, $value)
	{
		/* Проверка */
		self::is($id);
		self::_check($name, $identified, $value);
		
		/* Уникальность */
		$old = self::get($id);
		self::_unique($name, $identified, $old['Module_ID'], $id);

		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Identified" => $identified,
			"Value" => $value
		];
		G::db_core()->update("text", $data, ["ID" => $id]);
		
		/* Удалить кэш */
		G::cache_db_core()->delete_tag("text");

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

		G::db_core()->delete("text", ["ID" => $id]);
		
		/* Удалить кэш */
		G::cache_db_core()->delete_tag("text");

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
	"Value",
	"Module_ID"
FROM 
	"text"
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
	"Value",
	"Module_ID"
FROM 
	"text"
WHERE 
	"Module_ID" = $1
ORDER BY
	"Identified" ASC
SQL;
		return G::db_core()->query($query, $module_id)->assoc();
	}
	
	/**
	 * Получить все системные тексты
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
	"Identified"
FROM 
	"text"
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
	 * @param string $value
	 */
	private static function _check($name, &$identified, $value)
	{
		Err::check_field($name, "string", false, "Name", "Наименование");
		
		Err::check_field($identified, "identified", false, "Identified", "Идентификатор");
		$identified = strtolower($identified);
		
		Err::check_field($value, "html", true, "Value", "Значение");
		
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
	"text"
WHERE 
	"Name" ILIKE $1 AND 
	COALESCE ("Module_ID", 0) = $2 AND
	"ID" != $3
SQL;
		$rec = G::db_core()->query($query, [$name, (int)$module_id, (int)$id])->single();
		if ($rec !== null)
		{
			Err::add("Текст с полем «Наименование» : «{$name}» уже существует.", "Name");
		}

		$query = 
<<<SQL
SELECT 
	true
FROM 
	"text"
WHERE 
	"Identified" = $1 AND
	COALESCE ("Module_ID", 0) = $2 AND
	"ID" != $3
SQL;
		$rec = G::db_core()->query($query, [$identified, (int)$module_id, (int)$id])->single();
		if ($rec !== null)
		{
			Err::add("Текст с полем «Идентификатор» : «{$identified}» уже существует.", "Identified");
		}
		
		Err::exception();
	}
}
?>