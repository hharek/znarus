<?php
/**
 * Модуль
 */
class _Module
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
			throw new Exception("Номер у модуля задан неверно. " . Type::get_last_error());
		}

		$query = 
<<<SQL
SELECT 
	true
FROM 
	"module"
WHERE 
	"ID" = $1
SQL;
		$rec = G::db_core()->query($query, $id)->single();
		if ($rec === null)
		{
			throw new Exception("Модуля с номером «{$id}» не существует.");
		}
	}

	/**
	 * Добавить
	 * 
	 * @param string $name
	 * @param string $identified
	 * @param text $description
	 * @param string $version
	 * @return array
	 */
	public static function add($name, $identified, $description, $version)
	{
		/* Проверка */
		self::_check($name, $identified, $description, $version);

		/* Уникальность */
		self::_unique($name, $identified);

		/* Создать папки и файлы */
		if ($identified[0] === "_")
		{
			G::file_app()->mkdir("smod/" . $identified);
		}
		else
		{
			G::file_app()->mkdir("mod/" . $identified);
		}

		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Identified" => $identified,
			"Description" => $description,
			"Version" => $version
		];
		$id = G::db_core()->insert("module", $data, "ID");
		
		/* Удалить кэш */
		G::cache_db_core()->delete_tag("module");

		/* Данные добавленного */
		return self::get($id);
	}

	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $name
	 * @param string $identified
	 * @param text $description
	 * @param string $version
	 * @param string $access
	 * @param string $page_info_function
	 * @param bool $active
	 * @return array
	 */
	public static function edit($id, $name, $identified, $description, $version, $access, $page_info_function, $active)
	{
		/* Проверка */
		self::is($id);
		self::_check($name, $identified, $description, $version, $access, $page_info_function, $active);
		
		/* Префикс «_» */
		$old = self::get($id);
		if ($old['Type'] === "mod" and $identified[0] === "_")
		{
			Err::add("Модуль не должен начинаться с символа «_»", "Identified");
		}
		elseif ($old['Type'] === "smod" and $identified[0] !== "_")
		{
			Err::add("Системный модуль должен начинаться с символа «_»", "Identified");
		}
		Err::exception();
		
		/* При деактивации проверить не отвечает ли модуль за стандартные маршруты */
		if ((bool)$old['Active'] === true and (bool)$active === false)
		{
			if (in_array($old['Identified'], [P::get("home_module"), P::get("404_module"), P::get("403_module")]))
			{
				throw new Exception("Нельзя деактивировать модуль, который отвечает за отображение стандартных маршрутов (home, 404, 403).");
			}
		}

		/* Уникальность */
		self::_unique($name, $identified, $id);

		/* Переименовать файлы */
		if ($old['Identified'] !== $identified)
		{
			G::file_app()->mv
			(
				$old['Type'] . "/" . $old['Identified'], 
				$old['Type'] . "/" . $identified
			);
		}

		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Identified" => $identified,
			"Description" => $description,
			"Version" => $version,
			"Access" => $access,
			"Page_Info_Function" => $page_info_function,
			"Active" => $active
		];
		G::db_core()->update("module", $data, ["ID" => $id]);
		
		/* Если модуль отвечает за стандартные маршруты и идентфикатор изменился */
		if ($old['Identified'] !== $identified)
		{
			if ($old['Identified'] === P::get("home_module"))
			{
				P::set("home_module", $identified);
			}
			if ($old['Identified'] === P::get("404_module"))
			{
				P::set("404_module", $identified);
			}
			if ($old['Identified'] === P::get("403_module"))
			{
				P::set("403_module", $identified);
			}
		}
		
		/* Удалить кэш */
		G::cache_db_core()->delete_tag("module");
		_Cache_Front::delete(["module" => $old['Identified']]);

		/* Данные изменённого */
		return self::get($id);
	}

	/**
	 * Удалить
	 * 
	 * @param int $id
	 */
	public static function delete($id)
	{
		/* Проверка */
		self::is($id);

		/* Удаление зависемостей */
		$param = _Param::get_by_module($id);
		if (!empty($param))
		{
			foreach ($param as $val)
			{
				_Param::delete($val['ID']);
			}
		}

		$admin = _Admin::get_by_module($id);
		if (!empty($admin))
		{
			foreach ($admin as $val)
			{
				_Admin::delete($val['ID']);
			}
		}

		$exe = _Exe::get_by_module($id);
		if (!empty($exe))
		{
			foreach ($exe as $val)
			{
				_Exe::delete($val['ID']);
			}
		}

		$inc = _Inc::get_by_module($id);
		if (!empty($inc))
		{
			foreach ($inc as $val)
			{
				_Inc::delete($val['ID']);
			}
		}

		$proc = _Proc::get_by_module($id);
		if (!empty($proc))
		{
			foreach ($proc as $val)
			{
				_Proc::delete($val['ID']);
			}
		}

		$ajax = _Ajax::get_by_module($id);
		if (!empty($ajax))
		{
			foreach ($ajax as $val)
			{
				_Ajax::delete($val['ID']);
			}
		}

		$text = _Text::get_by_module($id);
		if (!empty($text))
		{
			foreach ($text as $val)
			{
				_Text::delete($val['ID']);
			}
		}

		/* Удалить папку */
		$old = self::get($id);
		G::file_app()->rm($old['Type'] . "/" . $old['Identified']);

		/* SQL */
		G::db_core()->delete("module", ["ID" => $id]);
		
		/* Удалить кэш */
		G::cache_db_core()->delete_tag("module");
		_Cache_Front::delete(["module" => $old['Identified']]);

		/* Данные удалённого */
		return $old;
	}

	/**
	 * Проверка по идентификатору
	 * 
	 * @param string $identified
	 */
	public static function is_identified($identified)
	{
		if (!Type::check("identified", $identified))
		{
			throw new Exception("Идентификатор у модуля задан неверно. " . Type::get_last_error());
		}
		$identified = strtolower($identified);

		$query = 
<<<SQL
SELECT 
	true
FROM 
	"module"
WHERE 
	"Identified" = $1
SQL;
		$rec = G::db_core()->query($query, $identified)->single();
		if ($rec === null)
		{
			throw new Exception("Модуля с идентификатором «{$identified}» не существует.");
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
	"Identified", 
	"Description", 
	"Version", 
	"Active"::int,
	CASE LEFT("Identified", 1)
		WHEN '_' THEN 'smod'
		ELSE 'mod'
	END as "Type",
	"Access",
	"Page_Info_Function"
FROM 
	"module"
WHERE 
	"ID" = $1
SQL;
		return G::db_core()->query($query, $id)->row();
	}

	/**
	 * Выборка всех
	 * 
	 * @param string $type (all|mod|smod)
	 * @return array
	 */
	public static function get_by_type($type = "all", $only_active = false, $access = "all")
	{
		/* Проверка */
		if (!in_array($type, ["all", "smod", "mod"]))
		{
			throw new Exception("Тип модулей задан неверно.");
		}
		
		$only_active = (int)(bool)$only_active;
				
		if (!in_array($access, ["all", "no", "local", "global"]))
		{
			throw new Exception("Влияние на доступ указано неверно.");
		}
		
		/* SQL */
		$module = G::cache_db_core()->get("module_" . $type . "_" . $only_active . "_" . $access);
		if ($module === null)
		{
			$module = G::db_core()->module_by_type($type, $only_active, $access)->assoc();
			G::cache_db_core()->set("module_" . $type . "_" . $only_active . "_" . $access, $module, "module");
		}
		
		return $module;
	}
	
	/**
	 * Получить тип модуля по идентификатору
	 * 
	 * @param string $identified
	 * @return string
	 */
	public static function get_type_by_identified($identified)
	{
		if ($identified[0] === "_")
		{
			return "smod";
		}
		else
		{
			return "mod";
		}
	}
	
	/**
	 * Получить модуль по идентификатору
	 * 
	 * @param string $identified
	 * @return array
	 */
	public static function get_by_identified($identified)
	{
		/* Проверка */
		if (!Type::check("identified", $identified))
		{
			throw new Exception("Идентификатор модуля задан неверно.");
		}
		
		/* Выборка */
		$module = G::cache_db_core()->get("module_by_identified_" . $identified);
		if ($module === null)
		{
			$module = G::db_core()->module_by_identified($identified)->row();
			if(empty($module))
			{
				throw new Exception("Модуля с идентификатором «{$identified}» не существует.");
			}
			G::cache_db_core()->set("module_by_identified_" . $identified, $module, "module");
		}
		
		return $module;
	}

	/**
	 * Проверка полей
	 * 
	 * @param string $name
	 * @param string $identified
	 * @param text $description
	 * @param string $version
	 * @param string $access
	 * @param string $page_info_function
	 * @param bool $active
	 */
	private static function _check($name, &$identified, $description, $version, $access = null, &$page_info_function = null, $active = null)
	{
		Err::check_field($name, "string", false, "Name", "Наименование");
		
		Err::check_field($identified, "identified", false, "Identified", "Идентификатор");
		$identified = strtolower($identified);
		
		Err::check_field($description, "text", true, "Description", "Описание");
		Err::check_field($version, "string", false, "Version", "Версия");
		
		if ($access !== null)
		{
			if (!in_array($access, ["no", "local", "global"]))
			{
				Err::add("Влияние на доступ указано неверно.", "Access");
			}
		}
		
		if ($page_info_function !== null)
		{
			/* Функция */
			if (!empty($page_info_function) and strpos($page_info_function, "::") === false)
			{
				if (!function_exists($page_info_function))
				{
					Err::add("Функция «{$page_info_function}» отсутствует.", "Page_Info_Function");
				}
			}
			/* Метод */
			elseif (!empty ($page_info_function) and strpos($page_info_function, "::") !== false)
			{
				$page_info_function_ar = explode("::", $page_info_function);
				if (count($page_info_function_ar) === 2)
				{
					if (!method_exists($page_info_function_ar[0], $page_info_function_ar[1]))
					{
						Err::add("Метод «{$page_info_function}» отсутствует.", "Page_Info_Function");
					}
				}
				else
				{
					Err::add("Фукнция Page_Info указана неверно.", "Page_Info_Function");
				}
			}
			elseif (empty ($page_info_function))
			{
				$page_info_function = null;
			}
		}
		
		if ($active !== null)
		{
			Err::check_field($active, "bool", false, "Active", "Активен");
		}
		
		Err::exception();
	}

	/**
	 * Уникальность
	 * 
	 * @param string $name
	 * @param string $identified
	 * @param int $id
	 */
	private static function _unique($name, $identified, $id = null)
	{
		$query = 
<<<SQL
SELECT 
	true
FROM 
	"module"
WHERE 
	"Name" ILIKE $1 AND
	"ID" != $2
SQL;
		$rec = G::db_core()->query($query, [$name, (int)$id])->single();
		if ($rec !== null)
		{
			Err::add("Модуль с полем «Наименование» : «{$name}» уже существует.", "Name");
		}

		$query = 
<<<SQL
SELECT 
	true
FROM 
	"module"
WHERE 
	"Identified" = $1 AND
	"ID" != $2
SQL;
		$rec = G::db_core()->query($query, [$identified, (int)$id])->single();
		if ($rec !== null)
		{
			Err::add("Модуль с полем «Идентификатор» : «{$identified}» уже существует.", "Identified");
		}

		Err::exception();
	}
}
?>