<?php
/**
 * Исполнители
 */
class _Exe
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
			throw new Exception("Номер у исполнителя задан неверно. " . Chf::error());
		}

		$query = 
<<<SQL
SELECT 
	true
FROM 
	"exe"
WHERE 
	"ID" = $1
SQL;
		$rec = G::db_core()->query($query, $id)->single();
		if ($rec === null)
		{
			throw new Exception("Исполнителя с номером «{$id}» не существует.");
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
		self::_file_add($identified, $module_id);
		
		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Identified" => $identified,
			"Module_ID" => $module_id
		];
		$id = G::db_core()->insert("exe", $data, "ID");
		
		/* Удалить кэш */
		G::cache_db_core()->delete_tag("exe");

		/* Данные добавленного */
		return self::get($id);
	}

	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $name
	 * @param string $identified
	 * @param bool $cache_route
	 * @param bool $cache_page
	 * @param bool $active
	 * @return array
	 */
	public static function edit($id, $name, $identified, $cache_route, $cache_page, $active)
	{
		/* Проверка */
		self::is($id);
		self::_check($name, $identified, $cache_route, $cache_page, $active);
		
		/* Уникальность */
		$old = self::get($id);
		$module = _Module::get($old['Module_ID']);
		
		self::_unique($name, $identified, $old['Module_ID'], $id);
		
		/* При деактивации проверить не является ли exe модулём для стандартных маршрутов */
		if ((bool)$old['Active'] === true and (bool)$active === false)
		{
			if ($module['Identified'] === P::get("home_module") and $old['Identified'] === P::get("home_exe"))
			{
				throw new Exception("Невозможно деактивировать исполнитель, отвечающий за отображение «Главной страницы».");
			}
			elseif ($module['Identified'] === P::get("404_module") and $old['Identified'] === P::get("404_exe"))
			{
				throw new Exception("Невозможно деактивировать исполнитель, отвечающий за отображение «Страницы 404».");
			}
			elseif ($module['Identified'] === P::get("403_module") and $old['Identified'] === P::get("403_exe"))
			{
				throw new Exception("Невозможно деактивировать исполнитель, отвечающий за отображение «Страницы 403».");
			}
		}

		/* Файлы */
		self::_file_edit($old['Identified'], $identified, $old['Module_ID']);
		
		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Identified" => $identified,
			"Cache_Route" => (int)$cache_route,
			"Cache_Page" => (int)$cache_page,
			"Active" => (int)$active
		];
		G::db_core()->update("exe", $data, ["ID" => $id]);
		
		/* Если исполнитель отвечает за стандарнтый маршрут и идентфикатор изменился */
		if ($old['Identified'] !== $identified)
		{
			if ($module['Identified'] === P::get("home_module") and $old['Identified'] === P::get("home_exe"))
			{
				P::set("home_exe", $identified);
			}
			if ($module['Identified'] === P::get("404_module") and $old['Identified'] === P::get("404_exe"))
			{
				P::set("404_exe", $identified);
			}
			if ($module['Identified'] === P::get("403_module") and $old['Identified'] === P::get("403_exe"))
			{
				P::set("403_exe", $identified);
			}
		}
		
		/* Удалить кэш */
		G::cache_db_core()->delete_tag("exe");
		_Cache_Front::delete(["exe" => $module['Identified'] . "_" . $old['Identified']]);

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
		$module = _Module::get($old['Module_ID']);
		
		/* При удалении проверить не является ли exe модулём для стандартных маршрутов */
		if ($module['Identified'] === P::get("home_module") and $old['Identified'] === P::get("home_exe"))
		{
			throw new Exception("Невозможно удалить исполнитель, отвечающий за отображение «Главной страницы».");
		}
		elseif ($module['Identified'] === P::get("404_module") and $old['Identified'] === P::get("404_exe"))
		{
			throw new Exception("Невозможно удалить исполнитель, отвечающий за отображение «Страницы 404».");
		}
		elseif ($module['Identified'] === P::get("403_module") and $old['Identified'] === P::get("403_exe"))
		{
			throw new Exception("Невозможно удалить исполнитель, отвечающий за отображение «Страницы 403».");
		}
		
		/* Файлы */
		self::_file_delete($old['Identified'], $old['Module_ID']);
		
		/* Удалить */
		G::db_core()->delete("exe", ["ID" => $id]);
		
		/* Удалить кэш */
		G::cache_db_core()->delete_tag("exe");
		_Cache_Front::delete(["exe" => $module['Identified'] . "_" . $old['Identified']]);

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
	"Module_ID",
	"Cache_Route"::int,
	"Cache_Page"::int,
	"Active"::int
FROM 
	"exe"
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
	"Cache_Route"::int,
	"Cache_Page"::int,
	"Active"::int
FROM 
	"exe"
WHERE 
	"Module_ID" = $1
ORDER BY
	"Identified" ASC
SQL;
		return G::db_core()->query($query, $module_id)->assoc();
	}
	
	/**
	 * Получить исполнителя по идентификатору
	 * 
	 * @param string $module_identified
	 * @param string $identified
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
			throw new Exception("Идентификатор исполнителя задан неверно.");
		}
		
		/* Выборка */
		$exe = G::cache_db_core()->get("exe_by_identified_{$module_identified}_{$identified}");
		if ($exe === null)
		{
			$exe = G::db_core()->exe_by_identified($module_identified, $identified)->row();
			if (empty($exe))
			{
				throw new Exception("Исполнитель «{$module_identified}» / «{$identified}» задан неверно.");
			}
			G::cache_db_core()->set("exe_by_identified_{$module_identified}_{$identified}", $exe, "exe");
		}
		
		return $exe;
	}

	/**
	 * Проверка полей
	 * 
	 * @param string $name
	 * @param string $identified
	 * @param bool $cache_route
	 * @param bool $cache_page
	 * @param string $active
	 */
	private static function _check($name, &$identified, &$cache_route = null, &$cache_page = null, &$active = null)
	{
		Err::check_field($name, "string", false, "Name", "Наименование");
		Err::check_field($identified, "identified", false, "Identified", "Идентификатор");
		$identified = strtolower($identified);
		
		if ($cache_route !== null)
		{
			Err::check_field($cache_route, "bool", false, "Cache_Route", "Кэшировать маршруты");
		}
		
		if ($cache_page !== null)
		{
			Err::check_field($cache_page, "bool", false, "Cache_Page", "Кэшировать страницы");
			
			if ((bool)$cache_route === false)
			{
				$cache_page = "0";
			}
		}
		
		if ($active !== null)
		{
			Err::check_field($active, "bool", false, "Active", "Активность");
		}
		$active = (bool)$active;
		
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
	"exe"
WHERE 
	"Name" ILIKE $1 AND 
	"Module_ID" = $2 AND
	"ID" != $3
SQL;
		$rec = G::db_core()->query($query, [$name, $module_id, (int)$id])->single();
		if ($rec !== null)
		{
			Err::add("Исполнитель с полем «Наименование» : «{$name}» уже существует.", "Name");
		}

		$query = 
<<<SQL
SELECT 
	true
FROM 
	"exe"
WHERE 
	"Identified" = $1 AND
	"Module_ID" = $2 AND
	"ID" != $3
SQL;
		$rec = G::db_core()->query($query, [$identified, $module_id, (int)$id])->single();
		if ($rec !== null)
		{
			Err::add("Исполнитель с полем «Идентификатор» : «{$identified}» уже существует.", "Identified");
		}
		
		Err::exception();
	}
	
	/**
	 * Добавить файл
	 * 
	 * @param string $identified
	 * @param int $module_id
	 */
	private static function _file_add($identified, $module_id)
	{
		$module = _Module::get($module_id);
		$path_exe = $module['Type'] . "/" . $module['Identified'] . "/exe";

		/* Создать папку exe если нет */
		if (!G::file_app()->is_dir($path_exe))
		{
			G::file_app()->mkdir($path_exe);
		}

		/* Создать файлы */
		if (!G::file_app()->is_dir($path_exe . "/act"))
		{
			G::file_app()->mkdir($path_exe . "/act");
		}

		G::file_app()->put($path_exe . "/act/". $identified. ".php", "<?php\n\n?>");

		if (!G::file_app()->is_dir($path_exe . "/html"))
		{
			G::file_app()->mkdir($path_exe . "/html");
		}

		G::file_app()->put($path_exe . "/html/{$identified}.html", "");
	}

	/**
	 * Переименовать файлы
	 * 
	 * @param string $identified_old
	 * @param string $identified_new
	 * @param int $module_id
	 */
	private static function _file_edit($identified_old, $identified_new, $module_id)
	{
		$module = _Module::get($module_id);
		$path_exe = $module['Type'] . "/" . $module['Identified'] . "/exe";

		G::file_app()->mv
		(
			$path_exe . "/act/" . $identified_old . ".php", 
			$path_exe . "/act/" . $identified_new . ".php"
		);

		G::file_app()->mv
		(
			$path_exe . "/html/" . $identified_old . ".html", 
			$path_exe . "/html/" . $identified_new . ".html"
		);
	}

	/**
	 * Удалить файлы
	 * 
	 * @param type $identified
	 * @param type $module_id
	 */
	private static function _file_delete($identified, $module_id)
	{
		$module = _Module::get($module_id);
		$path_exe = $module['Type'] . "/" . $module['Identified'] . "/exe";

		/* Удалить файлы */
		G::file_app()->rm($path_exe . "/act/" . $identified . ".php");
		G::file_app()->rm($path_exe . "/html/" . $identified . ".html");
		
		/* Удалить папку если act,html если они пустые */
		if (G::file_app()->is_dir($path_exe . "/act"))
		{
			if (count(G::file_app()->ls($path_exe . "/act")) === 0)
			{
				G::file_app()->rm($path_exe . "/act");
			}
		}
		
		if (G::file_app()->is_dir($path_exe . "/html"))
		{
			if (count(G::file_app()->ls($path_exe . "/html")) === 0)
			{
				G::file_app()->rm($path_exe . "/html");
			}
		}
		
		/* Удалить папку exe если нет исполнителей */
		if (count (G::file_app()->ls($path_exe)) === 0)
		{
			G::file_app()->rm($path_exe);
		}
	}
}
?>