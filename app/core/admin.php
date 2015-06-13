<?php
/**
 * Исполнители
 */
class _Admin
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
			throw new Exception("Номер у админки задан неверно. " . Chf::error());
		}

		$query = 
<<<SQL
SELECT 
	true
FROM 
	"admin"
WHERE 
	"ID" = $1
SQL;
		$rec = G::db_core()->query($query, $id)->single();
		if ($rec === null)
		{
			throw new Exception("Админки с номером «{$id}» не существует.");
		}
	}
	
	/**
	 * Добавить
	 * 
	 * @param string $name
	 * @param string $identified
	 * @param bool $get
	 * @param bool $post
	 * @param bool $visible
	 * @param int $module_id
	 * @return array
	 */
	public static function add($name, $identified, $get, $post, $visible, $module_id)
	{
		/* Проверка */
		self::_check($name, $identified, $get, $post, $visible);
		_Module::is($module_id);

		/* Уникальность */
		self::_unique($name, $identified, $module_id);
		
		/* Файлы */
		if ($get === true)
		{
			self::file_add($identified, "get", $module_id);
		}

		if ($post === true)
		{
			self::file_add($identified, "post", $module_id);
		}

		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Identified" => $identified,
			"Get" => (int)$get,
			"Post" => (int)$post,
			"Visible" => (int)$visible,
			"Module_ID" => $module_id
		];
		$id = G::db_core()->insert("admin", $data, "ID");

		/* Данные добавленного */
		return self::get($id);
	}

	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $name
	 * @param string $identified
	 * @param bool $get
	 * @param bool $post
	 * @param bool $visible
	 * @param bool $window
	 * @param bool $allow_all
	 * @return array
	 */
	public static function edit($id, $name, $identified, $get, $post, $visible, $window, $allow_all)
	{
		/* Проверка */
		self::is($id);
		self::_check($name, $identified, $get, $post, $visible, $window, $allow_all);
		
		/* Уникальность */
		$old = self::get($id);
		self::_unique($name, $identified, $old['Module_ID'], $id);

		/* Файлы GET */
		if ((bool)$old['Get'] === false and $get === true)
		{
			self::file_add($identified, "get", $old['Module_ID']);
		}
		elseif ((bool)$old['Get'] === true and $get === true)
		{
			if ($old['Identified'] !== $identified)
			{
				self::file_edit($old['Identified'], $identified, "get", $old['Module_ID']);
			}
		}
		elseif ((bool)$old['Get'] === true and $get === false)
		{
			self::file_delete($old['Identified'], "get", $old['Module_ID']);
		}

		/* Файлы POST */
		if ((bool)$old['Post'] === false and $post === true)
		{
			self::file_add($identified, "post", $old['Module_ID']);
		}
		elseif ((bool)$old['Post'] === true and $post === true)
		{
			if ($old['Identified'] !== $identified)
			{
				self::file_edit($old['Identified'], $identified, "post", $old['Module_ID']);
			}
		}
		elseif ((bool)$old['Post'] === true and $post === false)
		{
			self::file_delete($old['Identified'], "post", $old['Module_ID']);
		}

		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Identified" => $identified,
			"Get" => (int)$get,
			"Post" => (int)$post,
			"Visible" => (int)$visible,
			"Window" => (int)$window,
			"Allow_All" => (int)$allow_all
		];
		G::db_core()->update("admin", $data, ["ID" => $id]);

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

		/* Файлы */
		if ((bool)$old['Get'] === true)
		{
			self::file_delete($old['Identified'], "get", $old['Module_ID']);
		}

		if ((bool)$old['Post'] === true)
		{
			self::file_delete($old['Identified'], "post", $old['Module_ID']);
		}

		/* Удалить */
		G::db_core()->delete("admin", ["ID" => $id]);

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
	"Get"::int,
	"Post"::int,
	"Visible"::int,
	"Module_ID",
	"Sort",
	"Window"::int,
	"Allow_All"::int
FROM 
	"admin"
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
	"Get"::int,
	"Post"::int,
	"Visible"::int,
	"Module_ID",
	"Sort",
	"Window"::int,
	"Allow_All"::int
FROM 
	"admin"
WHERE 
	"Module_ID" = $1
ORDER BY
	"Sort" ASC
SQL;
		return G::db_core()->query($query, $module_id)->assoc();
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
			G::db_core()->update("admin", $data, ["ID" => $id]);
		}
		else
		{
			$admin = self::get($id);

			$query = 
<<<SQL
SELECT 
	"ID", 
	"Sort"
FROM 
	"admin"
WHERE 
	"Module_ID" = $1
ORDER BY 
	"Sort" ASC
SQL;
			$other = G::db_core()->query($query, $admin['Module_ID'])->assoc();

			if (count($other) < 2)
			{
				throw new Exception("Необходимо хотя бы два исполнителя.");
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
			G::db_core()->update("admin", $data, ["ID" => $id]);

			$data = 
			[
				"Sort" => $sort_int_next
			];
			G::db_core()->update("admin", $data, ["ID" => $id_next]);
		}
	}

	/**
	 * Добавить файл
	 * 
	 * @param string $identified
	 * @param string $type
	 * @param int $module_id
	 */
	private static function file_add($identified, $type, $module_id)
	{
		$module = _Module::get($module_id);
		$path_admin = "{$module['Type']}/{$module['Identified']}/admin";

		/* Создать папку admin если нет */
		if (!G::file_app()->is_dir($path_admin))
		{
			G::file_app()->mkdir($path_admin);
		}

		/* Создать файлы для GET запроса */
		if ($type === "get")
		{
			if (!G::file_app()->is_dir($path_admin . "/get"))
			{
				G::file_app()->mkdir($path_admin . "/get");
			}
			
			G::file_app()->put($path_admin . "/get/{$identified}.php", "<?php\n\n?>");

			if (!G::file_app()->is_dir($path_admin . "/html"))
			{
				G::file_app()->mkdir($path_admin . "/html");
			}

			G::file_app()->put($path_admin . "/html/{$identified}.html", "");
		}
		/* Создать файлы для POST запроса */
		elseif ($type === "post")
		{
			if (!G::file_app()->is_dir($path_admin . "/post"))
			{
				G::file_app()->mkdir($path_admin . "/post");
			}
			
			G::file_app()->put($path_admin . "/post/{$identified}.php", "<?php\n\n?>");
		}
	}

	/**
	 * Переименовать файлы
	 * 
	 * @param string $old
	 * @param string $identified_old
	 * @param string $identified_new
	 * @param string $type
	 * @param int $module_id
	 */
	private static function file_edit($identified_old, $identified_new, $type, $module_id)
	{
		$module = _Module::get($module_id);
		$path_admin = "{$module['Type']}/{$module['Identified']}/admin";

		if ($type === "get")
		{
			G::file_app()->mv
			(
				$path_admin . "/get/{$identified_old}.php", 
				$path_admin . "/get/{$identified_new}.php"
			);

			G::file_app()->mv
			(
				$path_admin . "/html/{$identified_old}.html", 
				$path_admin . "/html/{$identified_new}.html"
			);
		}
		elseif ($type === "post")
		{
			G::file_app()->mv
			(
				$path_admin . "/post/{$identified_old}.php", 
				$path_admin . "/post/{$identified_new}.php"
			);
		}
	}

	/**
	 * Удалить файлы
	 * 
	 * @param type $identified
	 * @param type $type
	 * @param type $module_id
	 */
	private static function file_delete($identified, $type, $module_id)
	{
		$module = _Module::get($module_id);
		$path_admin = "{$module['Type']}/{$module['Identified']}/admin";

		/* Удалить файлы */
		if ($type === "get")
		{
			G::file_app()->rm($path_admin . "/get/{$identified}.php");
			G::file_app()->rm($path_admin . "/html/{$identified}.html");
		}
		elseif ($type === "post")
		{
			G::file_app()->rm($path_admin . "/post/{$identified}.php");
		}

		/* Удалить папку если get, post, html если они пустые */
		if (G::file_app()->is_dir($path_admin . "/get"))
		{
			if (count(G::file_app()->ls($path_admin . "/get")) === 0)
			{
				G::file_app()->rm($path_admin . "/get");
			}
		}
		
		if (G::file_app()->is_dir($path_admin . "/html"))
		{
			if (count(G::file_app()->ls($path_admin . "/html")) === 0)
			{
				G::file_app()->rm($path_admin . "/html");
			}
		}
		
		if (G::file_app()->is_dir($path_admin . "/post"))
		{
			if (count(G::file_app()->ls($path_admin . "/post")) === 0)
			{
				G::file_app()->rm($path_admin . "/post");
			}
		}
		
		/* Удалить папку admin если админок нет */
		if (count (G::file_app()->ls($path_admin)) === 0)
		{
			G::file_app()->rm($path_admin);
		}
	}

	/**
	 * Проверка полей
	 * 
	 * @param string $name
	 * @param string $identified
	 * @param bool $get
	 * @param bool $post
	 * @param bool $visible
	 * @param int $module_id
	 */
	private static function _check($name, &$identified, &$get, &$post, &$visible, &$window = null, &$allow_all = null)
	{
		Err::check_field($name, "string", false, "Name", "Наименование");
		
		Err::check_field($identified, "identified", false, "Identified", "Идентификатор");
		$identified = strtolower($identified);
		
		Err::check_field($get, "bool", false, "Get", "Метод GET");
		$get = (bool)$get;
		
		Err::check_field($post, "bool", false, "Post", "Метод POST");
		$post = (bool)$post;
		
		if ($get === false and $post === false)
		{
			Err::add("Необходимо выбрать метод GET или метод POST", "Get");
			Err::add("Необходимо выбрать метод GET или метод POST", "Post");
		}
		
		Err::check_field($visible, "bool", false, "Visible", "Видимость");
		$visible = (bool)$visible;
		
		if ($window !== null)
		{
			Err::check_field($window, "bool", false, "Window", "В новом окне");
			$window = (bool)$window;
		}
		
		if ($allow_all !== null)
		{
			Err::check_field($allow_all, "bool", false, "Allow_All", "Разрешино всем");
			$allow_all = (bool)$allow_all;
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
	"admin"
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
	"admin"
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