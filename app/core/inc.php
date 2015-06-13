<?php
/**
 * Инки
 */
class _Inc
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
			throw new Exception("Номер у инка задан неверно. " . Chf::error());
		}

		$query = 
<<<SQL
SELECT 
	true
FROM 
	"inc"
WHERE 
	"ID" = $1
SQL;
		$rec = G::db_core()->query($query, $id)->single();
		if ($rec === null)
		{
			throw new Exception("Инка с номером «{$id}» не существует.");
		}
	}

	/**
	 * Добавить
	 * 
	 * @param string $name
	 * @param string $identified
	 * @param int $module_id
	 * @return array
	 */
	public static function add($name, $identified, $module_id)
	{
		/* Проверка */
		self::_check($name, $identified);
		_Module::is($module_id);

		/* Уникальность */
		self::_unique($name, $identified, $module_id);

		/* Файлы */
		$module = _Module::get($module_id);
		$dir_inc = $module['Type'] . "/" . $module['Identified'] . "/inc";

		if (!G::file_app()->is_dir($dir_inc))
		{
			G::file_app()->mkdir($dir_inc);
		}

		if (!G::file_app()->is_dir($dir_inc . "/act"))
		{
			G::file_app()->mkdir($dir_inc . "/act");
		}

		G::file_app()->put($dir_inc . "/act/" . $identified . ".php", "<?php\n?>");

		if (!G::file_app()->is_dir($dir_inc . "/html"))
		{
			G::file_app()->mkdir($dir_inc . "/html");
		}

		G::file_app()->put($dir_inc . "/html/" . $identified. ".html", "");

		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Identified" => $identified,
			"Module_ID" => $module_id
		];
		$id = G::db_core()->insert("inc", $data, "ID");
		
		/* Удалить кэш */
		G::cache_db_core()->delete_tag("inc");

		/* Данные добавленного */
		return self::get($id);
	}

	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $name
	 * @param string $identified
	 * @param bool $active
	 * @return array
	 */
	public static function edit($id, $name, $identified, $active)
	{
		/* Проверка */
		self::is($id);
		self::_check($name, $identified, $active);
		
		/* Уникальность */
		$inc = self::get($id);
		self::_unique($name, $identified, $inc['Module_ID'], $id);

		/* Файлы */
		$module = _Module::get($inc['Module_ID']);
		$dir_inc = $module['Type'] . "/" . $module['Identified'] . "/inc";
		G::file_app()->mv
		(
			$dir_inc . "/act/" . $inc['Identified'] . ".php", 
			$dir_inc . "/act/" . $identified . ".php"
		);

		G::file_app()->mv
		(
			$dir_inc . "/html/".  $inc['Identified'] . ".html", 
			$dir_inc . "/html/" . $identified . ".html"
		);

		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Identified" => $identified,
			"Active" => (int)$active
		];
		G::db_core()->update("inc", $data, ["ID" => $id]);
		
		/* Удалить кэш */
		G::cache_db_core()->delete_tag("inc");

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
		$inc = self::get($id);

		/* Файлы */
		$module = _Module::get($inc['Module_ID']);
		$dir_inc = $module['Type'] . "/" . $module['Identified'] . "/inc";
		
		G::file_app()->rm($dir_inc . "/act/" . $inc['Identified'] . ".php");
		if (count(G::file_app()->ls($dir_inc . "/act")) === 0)
		{
			G::file_app()->rm($dir_inc . "/act");
		}

		G::file_app()->rm($dir_inc . "/html/" . $inc['Identified'] . ".html");
		if (count(G::file_app()->ls($dir_inc . "/html")) === 0)
		{
			G::file_app()->rm($dir_inc . "/html");
		}
		
		/* Удалить папку inc если пустая */
		if (count(G::file_app()->ls($dir_inc)) === 0)
		{
			G::file_app()->rm($dir_inc);
		}

		/* Удалить */
		G::db_core()->delete("inc", ["ID" => $id]);
		
		/* Удалить кэш */
		G::cache_db_core()->delete_tag("inc");

		/* Данные удалённого */
		return $inc;
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
	"Module_ID",
	"Active"::int
FROM 
	"inc"
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
	"Module_ID",
	"Active"::int
FROM 
	"inc"
WHERE 
	"Module_ID" = $1
ORDER BY
	"Identified" ASC
SQL;
		return G::db_core()->query($query, $module_id)->assoc();
	}
	
	/**
	 * Получить инк по идентфикатору
	 * 
	 * @param string $module_identified
	 * @param string $identified
	 * @return array
	 */
	public static function get_by_identified($module_identified, $identified)
	{
		/* Проверка */
		if (!Chf::identified($module_identified))
		{
			throw new Exception("Идентификатор модуля задан неверно.");
		}
		
		if (!Chf::identified($identified))
		{
			throw new Exception("Идентификатор инка задан неверно.");
		}
		
		/* Выборка */
		$inc = G::cache_db_core()->get("inc_" . $module_identified . "_" . $identified);
		if ($inc === null)
		{
			$inc = G::db_core()->inc_by_identified($module_identified, $identified)->row();
			if (empty($inc))
			{
				throw new Exception("Инк «{$module_identified}» / «{$identified}» не существует.");
			}
			G::cache_db_core()->set("inc_" . $module_identified . "_" . $identified, $inc, "inc");
		}
		
		return $inc;
	}

	/**
	 * Проверка полей
	 * 
	 * @param string $name
	 * @param string $identified
	 * @param bool $active
	 */
	private static function _check($name, &$identified, &$active = null)
	{
		Err::check_field($name, "string", false, "Name", "Наименование");
		
		Err::check_field($identified, "identified", false, "Identified", "Идентификатор");
		$identified = strtolower($identified);
		
		if ($active !== null)
		{
			Err::check_field($active, "bool", false, "Active", "Активность");
			$active = (bool)$active;
		}
		
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
	"inc"
WHERE 
	"Name" ILIKE $1 AND 
	"Module_ID" = $2 AND
	"ID" != $3
SQL;
		$rec = G::db_core()->query($query, [$name, $module_id, (int)$id])->single();
		if ($rec !== null)
		{
			Err::add("Инк с полем «Наименование» : «{$name}» уже существует.", "Name");
		}

		$query = 
<<<SQL
SELECT 
	true
FROM 
	"inc"
WHERE 
	"Identified" = $1 AND
	"Module_ID" = $2 AND
	"ID" != $3
SQL;
		$rec = G::db_core()->query($query, [$identified, $module_id, (int)$id])->single();
		if ($rec !== null)
		{
			Err::add("Инк с полем «Идентификатор» : «{$identified}» уже существует.", "Identified");
		}
		
		Err::exception();
	}
}
?>