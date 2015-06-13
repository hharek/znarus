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
		if (!Chf::uint($id))
		{
			throw new Exception("Номер у «Аякса» задан неверно. " . Chf::error());
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
	 * @param bool $get
	 * @param bool $post
	 * @param string $data_type
	 * @param bool $token
	 * @param int $module_id
	 * @return array
	 */
	public static function add($identified, $name, $get, $post, $data_type, $token, $module_id)
	{
		/* Проверка */
		self::_check($name, $identified, $get, $post, $data_type, $token);
		_Module::is($module_id);

		/* Уникальность */
		self::_unqiue($name, $identified, $module_id);

		/* Файлы */
		if ($get === true)
		{
			self::_file_add($identified, "get", $module_id);
		}

		if ($post === true)
		{
			self::_file_add($identified, "post", $module_id);
		}

		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Identified" => $identified,
			"Data_Type" => $data_type,
			"Get" => (int)$get,
			"Post" => (int)$post,
			"Token" => $token,
			"Module_ID" => $module_id
		];
		$id = G::db_core()->insert("ajax", $data, "ID");

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
	 * @param string $data_type
	 * @param bool $token
	 * @return array
	 */
	public static function edit($id, $identified, $name, $get, $post, $data_type, $token)
	{
		/* Проверка */
		self::is($id);
		self::_check($name, $identified, $get, $post, $data_type, $token);

		/* Уникальность */
		$old = self::get($id);
		self::_unqiue($name, $identified, $old['Module_ID'], $id);

		/* Файлы GET */
		if ((bool)$old['Get'] === false and $get === true)
		{
			self::_file_add($identified, "get", $old['Module_ID']);
		}
		elseif ((bool)$old['Get'] === true and $get === true)
		{
			if ($old['Identified'] !== $identified)
			{
				self::_file_edit($old['Identified'], $identified, "get", $old['Module_ID']);
			}
		}
		elseif ((bool)$old['Get'] === true and $get === false)
		{
			self::_file_delete($old['Identified'], "get", $old['Module_ID']);
		}

		/* Файлы POST */
		if ((bool)$old['Post'] === false and $post === true)
		{
			self::_file_add($identified, "post", $old['Module_ID']);
		}
		elseif ((bool)$old['Post'] === true and $post === true)
		{
			if ($old['Identified'] !== $identified)
			{
				self::_file_edit($old['Identified'], $identified, "post", $old['Module_ID']);
			}
		}
		elseif ((bool)$old['Post'] === true and $post === false)
		{
			self::_file_delete($old['Identified'], "post", $old['Module_ID']);
		}

		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Identified" => $identified,
			"Data_Type" => $data_type,
			"Get" => (int)$get,
			"Post" => (int)$post,
			"Token" => $token
		];
		G::db_core()->update("ajax", $data, ["ID" => $id]);

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
			self::_file_delete($old['Identified'], "get", $old['Module_ID']);
		}

		if ((bool)$old['Post'] === true)
		{
			self::_file_delete($old['Identified'], "post", $old['Module_ID']);
		}

		/* SQL */
		G::db_core()->delete("ajax", ["ID" => $id]);

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
	"Token"::int,
	"Module_ID"
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
	"Token"::int,
	"Module_ID"
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
	 * @param bool $get
	 * @param bool $post
	 * @param string $data_type
	 * @param bool $token
	 */
	private static function _check($name, &$identified, &$get, &$post, $data_type, $token)
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

		if (!in_array($data_type, ["html", "text", "json", "json_std"]))
		{
			Err::add("Тип возвращаемых данных задан неверно.", "Data_Type");
		}

		Err::check_field($token, "bool", false, "Token", "Токен");

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
	 * @param string $type
	 * @param int $module_id
	 */
	private static function _file_add($identified, $type, $module_id)
	{
		$module = _Module::get($module_id);
		$path_ajax = "{$module['Type']}/{$module['Identified']}/ajax";

		/* Создать папку ajax если нет */
		if (!G::file_app()->is_dir($path_ajax))
		{
			G::file_app()->mkdir($path_ajax);
		}

		/* Создать файлы для GET запроса */
		if ($type === "get")
		{
			if (!G::file_app()->is_dir($path_ajax . "/get"))
			{
				G::file_app()->mkdir($path_ajax . "/get");
			}
			
			G::file_app()->put($path_ajax . "/get/{$identified}.php", "<?php\n\n?>");

			if (!G::file_app()->is_dir($path_ajax . "/html"))
			{
				G::file_app()->mkdir($path_ajax . "/html");
			}

			G::file_app()->put($path_ajax . "/html/{$identified}.html", "");
		}
		/* Создать файлы для POST запроса */
		elseif ($type === "post")
		{
			if (!G::file_app()->is_dir($path_ajax . "/post"))
			{
				G::file_app()->mkdir($path_ajax . "/post");
			}
			
			G::file_app()->put($path_ajax . "/post/{$identified}.php", "<?php\n\n?>");
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
	private static function _file_edit($identified_old, $identified_new, $type, $module_id)
	{
		$module = _Module::get($module_id);
		$path_ajax = "{$module['Type']}/{$module['Identified']}/ajax";

		if ($type === "get")
		{
			G::file_app()->mv
			(
				$path_ajax . "/get/{$identified_old}.php", 
				$path_ajax . "/get/{$identified_new}.php"
			);

			G::file_app()->mv
			(
				$path_ajax . "/html/{$identified_old}.html", 
				$path_ajax . "/html/{$identified_new}.html"
			);
		}
		elseif ($type === "post")
		{
			G::file_app()->mv
			(
				$path_ajax . "/post/{$identified_old}.php", 
				$path_ajax . "/post/{$identified_new}.php"
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
	private static function _file_delete($identified, $type, $module_id)
	{
		$module = _Module::get($module_id);
		$path_ajax = "{$module['Type']}/{$module['Identified']}/ajax";

		/* Удалить файлы */
		if ($type === "get")
		{
			G::file_app()->rm($path_ajax . "/get/{$identified}.php");
			G::file_app()->rm($path_ajax . "/html/{$identified}.html");
		}
		elseif ($type === "post")
		{
			G::file_app()->rm($path_ajax . "/post/{$identified}.php");
		}

		/* Удалить папку если get, post, html если они пустые */
		if (G::file_app()->is_dir($path_ajax . "/get"))
		{
			if (count(G::file_app()->ls($path_ajax . "/get")) === 0)
			{
				G::file_app()->rm($path_ajax . "/get");
			}
		}
		
		if (G::file_app()->is_dir($path_ajax . "/html"))
		{
			if (count(G::file_app()->ls($path_ajax . "/html")) === 0)
			{
				G::file_app()->rm($path_ajax . "/html");
			}
		}
		
		if (G::file_app()->is_dir($path_ajax . "/post"))
		{
			if (count(G::file_app()->ls($path_ajax . "/post")) === 0)
			{
				G::file_app()->rm($path_ajax . "/post");
			}
		}
		
		/* Удалить папку ajax если админок нет */
		if (count (G::file_app()->ls($path_ajax)) === 0)
		{
			G::file_app()->rm($path_ajax);
		}
	}
}
?>