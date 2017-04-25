<?php
/**
 * Аяксы
 */
class _Ajax
{
	/**
	 * Проверка на существование
	 * 
	 * @param int $id
	 */
	public static function is($id)
	{
		if (!Type::check("uint", $id))
		{
			throw new Exception("Номер у «Аякса» задан неверно. " . Type::get_last_error());
		}

		$query = 
<<<SQL
SELECT 
	true
FROM 
	"ajax"
WHERE 
	"ID" = $1
SQL;
		$rec = G::db_core()->query($query, $id)->single();
		if ($rec === null)
		{
			throw new Exception("Аякса с номером «{$id}» не существует.");
		}
	}

	/**
	 * Добавить
	 * 
	 * @param string $identified
	 * @param string $name
	 * @param string $data_type
	 * @param bool $get
	 * @param bool $post
	 * @param int $module_id
	 * @return array
	 */
	public static function add($identified, $name, $data_type, $get, $post, $module_id)
	{
		/* Проверка */
		self::_check($name, $identified, $data_type, $get, $post);
		_Module::is($module_id);

		/* Уникальность */
		self::_unqiue($name, $identified, $module_id);
		
		/* Создать файлы */
		if (in_array($data_type, ['json','html','text']))
		{
			self::_file_add($identified, $data_type, $module_id);
		}
		elseif ($data_type === "json_page")
		{
			if ((bool)$get === true)
			{
				self::_file_add_json_page($identified, "get", $module_id);
			}

			if ((bool)$post === true)
			{
				self::_file_add_json_page($identified, "post", $module_id);
			}
		}

		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Identified" => $identified,
			"Data_Type" => $data_type,
			"Get" => $get,
			"Post" => $post,
			"Module_ID" => $module_id
		];
		$id = G::db_core()->insert("ajax", $data, "ID");
		
		/* Удалить кэш */
		G::cache_db()->delete_tag("ajax");
		
		return self::get($id);
	}

	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $identified
	 * @param string $name
	 * @param bool $get
	 * @param bool $post
	 * @param bool $cache
	 * @param bool $active
	 * @return array
	 */
	public static function edit($id, $identified, $name, $get, $post, $cache, $active)
	{
		/* Проверка */
		$old = self::get($id);
		self::_check($name, $identified, $old['Data_Type'], $get, $post, $cache, $active);

		/* Уникальность */
		self::_unqiue($name, $identified, $old['Module_ID'], $id);
		
		/* Файлы */
		if (in_array($old['Data_Type'], ['json','html','text']))
		{
			self::_file_edit($old['Identified'], $identified, $old['Data_Type'], $old['Module_ID']);
		}
		/* Файлы страничного JSON-а */
		elseif ($old['Data_Type'] === "json_page")
		{
			/* Файлы GET */
			if ((bool)$old['Get'] === false and (bool)$get === true)
			{
				self::_file_add_json_page($identified, "get", $old['Module_ID']);
			}
			elseif ((bool)$old['Get'] === true and (bool)$get === true)
			{
				if ($old['Identified'] !== $identified)
				{
					self::_file_edit_json_page($old['Identified'], $identified, "get", $old['Module_ID']);
				}
			}
			elseif ((bool)$old['Get'] === true and (bool)$get === false)
			{
				self::_file_delete_json_page($old['Identified'], "get", $old['Module_ID']);
			}
			
			/* Файлы POST */
			if ((bool)$old['Post'] === false and (bool)$post === true)
			{
				self::_file_add_json_page($identified, "post", $old['Module_ID']);
			}
			elseif ((bool)$old['Post'] === true and (bool)$post === true)
			{
				if ($old['Identified'] !== $identified)
				{
					self::_file_edit_json_page($old['Identified'], $identified, "post", $old['Module_ID']);
				}
			}
			elseif ((bool)$old['Post'] === true and (bool)$post === false)
			{
				self::_file_delete_json_page($old['Identified'], "post", $old['Module_ID']);
			}
		}

		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Identified" => $identified,
			"Get" => $get,
			"Post" => $post,
			"Cache" => $cache,
			"Active" => $active
		];
		G::db_core()->update("ajax", $data, ["ID" => $id]);
		
		/* Удалить кэш */
		G::cache_db()->delete_tag("ajax");

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
		if (in_array($old['Data_Type'], ['json','html','text']))
		{
			self::_file_delete($old['Identified'], $old['Data_Type'], $old['Module_ID']);
		}
		elseif ($old['Data_Type'] === "json_page")
		{
			if ((bool)$old['Get'] === true)
			{
				self::_file_delete_json_page($old['Identified'], "get", $old['Module_ID']);
			}

			if ((bool)$old['Post'] === true)
			{
				self::_file_delete_json_page($old['Identified'], "post", $old['Module_ID']);
			}
		}
		
		/* SQL */
		G::db_core()->delete("ajax", ["ID" => $id]);
		
		/* Удалить кэш */
		G::cache_db()->delete_tag("ajax");

		return $old;
	}

	/**
	 * Данные по аяксу
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
	"Data_Type",
	"Module_ID",
	"Cache"::int,
	"Active"::int
FROM
	"ajax"
WHERE
	"ID" = $1
SQL;
		return G::db_core()->query($query, $id)->row();
	}

	/**
	 * Получить все аяксы по модулю
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
	"Data_Type",
	"Module_ID",
	"Cache"::int,
	"Active"::int
FROM
	"ajax"
WHERE
	"Module_ID" = $1
ORDER BY
	"Identified" ASC 
SQL;
		return G::db_core()->query($query, $module_id)->assoc();
	}

	/**
	 * Проверка полей
	 * 
	 * @param string $name
	 * @param string $identified
	 * @param string $data_type
	 * @param bool $get
	 * @param bool $post
	 * @param bool $cache
	 * @param bool $active
	 */
	private static function _check($name, &$identified, $data_type, &$get, &$post, $cache = null, $active = null)
	{
		Err::check_field($name, "string", false, "Name", "Наименование");
		
		Err::check_field($identified, "identified", false, "Identified", "Идентификатор");
		$identified = strtolower($identified);

		if (!in_array($data_type, ["json","html","text","json_page"]))
		{
			Err::add("Тип возвращаемых данных задан неверно.", "Data_Type");
		}
		
		if ($data_type === "json_page")
		{
			Err::check_field($get, "bool", false, "Get", "Метод GET");
			Err::check_field($post, "bool", false, "Post", "Метод POST");
			
			if ((bool)$get === false and (bool)$post === false)
			{
				Err::add("Необходимо выбрать метод GET или метод POST", "Get");
				Err::add("Необходимо выбрать метод GET или метод POST", "Post");
			}
		}
		else
		{
			$get = null;
			$post = null;
		}
		
		if ($cache !== null)
		{
			Err::check_field($cache, "bool", false, "Cache", "Использовать кэширование");
		}
		
		if ($active !== null)
		{
			Err::check_field($active, "bool", false, "Active", "Активность");
		}

		Err::exception();
	}

	/**
	 * Проверка на уникальность
	 * 
	 * @param string $name
	 * @param string $identified
	 * @param int $module_id
	 * @param int $id
	 */
	private static function _unqiue($name, $identified, $module_id, $id = null)
	{
		$query = 
<<<SQL
SELECT 
	true
FROM 
	"ajax"
WHERE 
	"Name" ILIKE $1 AND
	"Module_ID" = $2 AND
	"ID" != $3
SQL;
		$rec = G::db_core()->query($query, [$name, $module_id, (int)$id])->single();
		if ($rec !== null)
		{
			Err::add("Аякс с полем «Наименование» : «{$name}» уже существует.", "Name");
		}

		$query = 
<<<SQL
SELECT 
	true
FROM 
	"ajax"
WHERE 
	"Identified" = $1 AND
	"Module_ID" = $2 AND
	"ID" != $3
SQL;
		$rec = G::db_core()->query($query, [$identified, $module_id, (int)$id])->single();
		if ($rec !== null)
		{
			Err::add("Аякс с полем «Идентификатор» : «{$identified}» уже существует.", "Identified");
		}

		Err::exception();
	}
	
	/**
	 * Добавить файл
	 * 
	 * @param string $identified
	 * @param string $data_type
	 * @param int $module_id
	 */
	private static function _file_add($identified, $data_type, $module_id)
	{
		$module = _Module::get($module_id);
		$dir_ajax = $module['Type'] . "/" . $module['Identified'] . "/ajax";
		$dir = $dir_ajax . "/" . $data_type;

		/* Создать папки ajax если нет */
		if (!G::file_app()->is_dir($dir_ajax))
		{
			G::file_app()->mkdir($dir_ajax);
		}
		
		if (!G::file_app()->is_dir($dir))
		{
			G::file_app()->mkdir($dir);
		}
		
		/* Создать основые файлы и папки */
		switch ($data_type)
		{
			case "json":
			{
				G::file_app()->put($dir . "/" . $identified . ".php", "<?php\n\n?>");
			}
			break;
		
			case "html":
			{
				if (!G::file_app()->is_dir($dir . "/act"))
				{
					G::file_app()->mkdir($dir . "/act");
				}

				G::file_app()->put($dir . "/act/" . $identified . ".php", "<?php\n\n?>");

				if (!G::file_app()->is_dir($dir . "/html"))
				{
					G::file_app()->mkdir($dir . "/html");
				}

				G::file_app()->put($dir . "/html/" . $identified . ".html", "");
			}
			break;
		
			case "text":
			{
				G::file_app()->put($dir . "/" . $identified . ".php", "<?php\n\n?>");
			}
			break;
		}
	}

	/**
	 * Переименовать файлы
	 * 
	 * @param string $old
	 * @param string $identified_old
	 * @param string $identified_new
	 * @param string $data_type
	 * @param int $module_id
	 */
	private static function _file_edit($identified_old, $identified_new, $data_type, $module_id)
	{
		$module = _Module::get($module_id);
		$dir_ajax = $module['Type'] . "/" . $module['Identified'] . "/ajax";
		$dir = $dir_ajax . "/" . $data_type;
		
		switch ($data_type)
		{
			case "json":
			{
				G::file_app()->mv
				(
					$dir . "/" . $identified_old . ".php",
					$dir . "/" . $identified_new . ".php"
				);
			}
			break;
		
			case "html":
			{
				G::file_app()->mv
				(
					$dir . "/act/" . $identified_old . ".php",
					$dir . "/act/" . $identified_new . ".php"
				);
				
				G::file_app()->mv
				(
					$dir . "/html/" . $identified_old . ".html",
					$dir . "/html/" . $identified_new . ".html"
				);
			}
			break;
		
			case "text":
			{
				G::file_app()->mv
				(
					$dir . "/" . $identified_old . ".php",
					$dir . "/" . $identified_new . ".php"
				);
			}
			break;
		}
	}

	/**
	 * Удалить файлы
	 * 
	 * @param type $identified
	 * @param type $data_type
	 * @param type $module_id
	 */
	private static function _file_delete($identified, $data_type, $module_id)
	{
		$module = _Module::get($module_id);
		$dir_ajax = $module['Type'] . "/" . $module['Identified'] . "/ajax";
		$dir = $dir_ajax . "/" . $data_type;
		
		/* Удаляем файлы и папки */
		switch ($data_type)
		{
			case "json":
			{
				G::file_app()->rm($dir . "/" . $identified . ".php");
			}
			break;
		
			case "html":
			{
				G::file_app()->rm($dir . "/act/" . $identified . ".php");
				if (count(G::file_app()->ls($dir . "/act")) === 0)
				{
					G::file_app()->rm($dir . "/act");
				}
				
				G::file_app()->rm($dir . "/html/" . $identified . ".html");
				if (count(G::file_app()->ls($dir . "/html")) === 0)
				{
					G::file_app()->rm($dir . "/html");
				}
			}
			break;
		
			case "text":
			{
				G::file_app()->rm($dir . "/" . $identified . ".php");
			}
			break;
		}
		
		/* Удаляем основные папки если пустые */
		if (count(G::file_app()->ls($dir)) === 0)
		{
			G::file_app()->rm($dir);
		}
		
		if (count(G::file_app()->ls($dir_ajax)) === 0)
		{
			G::file_app()->rm($dir_ajax);
		}
	}
	
	/**
	 * Добавить файлы для json_page
	 * 
	 * @param string $identified
	 * @param string $type
	 * @param int $module_id
	 */
	private static function _file_add_json_page($identified, $type, $module_id)
	{
		$module = _Module::get($module_id);
		$dir_ajax = $module['Type'] . "/" . $module['Identified'] . "/ajax";
		$dir = $dir_ajax . "/json_page" ;
		
		/* Создать основные папки если нет */
		if (!G::file_app()->is_dir($dir_ajax))
		{
			G::file_app()->mkdir($dir_ajax);
		}
		
		if (!G::file_app()->is_dir($dir))
		{
			G::file_app()->mkdir($dir);
		}

		/* Создать файлы для GET запроса */
		if ($type === "get")
		{
			if (!G::file_app()->is_dir($dir . "/get"))
			{
				G::file_app()->mkdir($dir . "/get");
			}
			
			G::file_app()->put($dir . "/get/" . $identified . ".php", "<?php\n\n?>");

			if (!G::file_app()->is_dir($dir . "/html"))
			{
				G::file_app()->mkdir($dir . "/html");
			}

			G::file_app()->put($dir . "/html/" . $identified . ".html", "");
		}
		/* Создать файлы для POST запроса */
		elseif ($type === "post")
		{
			if (!G::file_app()->is_dir($dir . "/post"))
			{
				G::file_app()->mkdir($dir . "/post");
			}
			
			G::file_app()->put($dir . "/post/" . $identified . ".php", "<?php\n\n?>");
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
	private static function _file_edit_json_page($identified_old, $identified_new, $type, $module_id)
	{
		$module = _Module::get($module_id);
		$dir_ajax = $module['Type'] . "/" . $module['Identified'] . "/ajax";
		$dir = $dir_ajax . "/json_page";

		if ($type === "get")
		{
			G::file_app()->mv
			(
				$dir . "/get/" . $identified_old . ".php", 
				$dir . "/get/" . $identified_new . ".php"
			);

			G::file_app()->mv
			(
				$dir . "/html/" . $identified_old . ".html", 
				$dir . "/html/" . $identified_new . ".html"
			);
		}
		elseif ($type === "post")
		{
			G::file_app()->mv
			(
				$dir . "/post/" . $identified_old . ".php", 
				$dir . "/post/" . $identified_new . ".php"
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
	private static function _file_delete_json_page($identified, $type, $module_id)
	{
		$module = _Module::get($module_id);
		$dir_ajax = $module['Type'] . "/" . $module['Identified'] . "/ajax";
		$dir = $dir_ajax . "/json_page";

		/* Удалить файлы */
		if ($type === "get")
		{
			G::file_app()->rm($dir . "/get/" . $identified . ".php");
			if (count(G::file_app()->ls($dir . "/get")) === 0)
			{
				G::file_app()->rm($dir . "/get");
			}

			G::file_app()->rm($dir . "/html/" . $identified . ".html");
			if (count(G::file_app()->ls($dir . "/html")) === 0)
			{
				G::file_app()->rm($dir . "/html");
			}
		}
		elseif ($type === "post")
		{
			G::file_app()->rm($dir . "/post/" . $identified . ".php");
			if (count(G::file_app()->ls($dir . "/post")) === 0)
			{
				G::file_app()->rm($dir . "/post");
			}
		}

		/* Удалить основные папки, если пустые */
		if (count (G::file_app()->ls($dir)) === 0)
		{
			G::file_app()->rm($dir);
		}
		
		if (count (G::file_app()->ls($dir_ajax)) === 0)
		{
			G::file_app()->rm($dir_ajax);
		}
	}
}
?>