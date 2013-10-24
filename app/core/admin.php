<?php
/**
 * Исполнители
 */
class ZN_Admin
{
	/**
	 * Добавить
	 * 
	 * @param string $name
	 * @param string $identified
	 * @param bool $get
	 * @param bool $post
	 * @param bool $visible
	 * @param int $module_id
	 * @return string
	 */
	public static function add($name, $identified, $get, $post, $visible, $module_id)
	{
		/* Проверка */
		Err::check_field($name, "string", false, "Name", "Наименование");
		Err::check_field($identified, "identified", false, "Identified", "Идентификатор");
		$identified = strtolower($identified);
		Err::check_field($get, "bool", false, "Get", "Метод GET");
		Err::check_field($post, "bool", false, "Post", "Метод POST");
		if($get === "0" and $post === "0")
		{
			Err::add("Необходимо выбрать метод GET или метод POST", "Get");
			Err::add("Необходимо выбрать метод GET или метод POST", "Post");
		}
		Err::check_field($visible, "bool", false, "Visible", "Видимость");
		ZN_Module::is_id($module_id);
		Err::exception();
		
		/* Уникальность */
		self::_unique($name, $identified, $module_id);
		Err::exception();
		
		/* Файлы */
		if($get === "1")
		{
			self::file_add($identified, "get", $module_id);
		}
		
		if ($post === "1") 
		{
			self::file_add($identified, "post", $module_id);
		}
		
		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Identified" => $identified,
			"Get" => $get,
			"Post" => $post,
			"Visible" => $visible,
			"Module_ID" => $module_id
		];
		$id = Reg::db_core()->insert("admin", $data, "ID");
		
		/* Данные добавленного */
		return self::select_line_by_id($id);
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
	 * @return array
	 */
	public static function edit($id, $name, $identified, $get, $post, $visible)
	{
		/* Проверка */
		self::is_id($id);
		Err::check_field($name, "string", false, "Name", "Наименование");
		Err::check_field($identified, "identified", false, "Identified", "Идентификатор");
		$identified = strtolower($identified);
		Err::check_field($get, "bool", false, "Get", "Метод GET");
		Err::check_field($post, "bool", false, "Post", "Метод POST");
		if($get === "0" and $post === "0")
		{
			Err::add("Необходимо выбрать метод GET или метод POST", "Get");
			Err::add("Необходимо выбрать метод GET или метод POST", "Post");
		}
		Err::check_field($visible, "bool", false, "Visible", "Видимость");
		Err::exception();
		
		/* Уникальность */
		$admin = self::select_line_by_id($id);
		self::_unique($name, $identified, $admin['Module_ID'], $id);
		Err::exception();
		
		/* Файлы GET */
		if($admin['Get'] === "0" and $get === "1")
		{
			self::file_add($identified, "get", $admin['Module_ID']);
		}
		elseif($admin['Get'] === "1" and $get === "1")
		{
			if($admin['Identified'] !== $identified)
			{
				self::file_edit($admin['Identified'], $identified, "get", $admin['Module_ID']);
			}
		}
		elseif($admin['Get'] === "1" and $get === "0")
		{
			self::file_delete($admin['Identified'], "get", $admin['Module_ID']);
		}
		
		/* Файлы POST */
		if($admin['Post'] === "0" and $post === "1")
		{
			self::file_add($identified, "post", $admin['Module_ID']);
		}
		elseif($admin['Post'] === "1" and $post === "1")
		{
			if($admin['Identified'] !== $identified)
			{
				self::file_edit($admin['Identified'], $identified, "post", $admin['Module_ID']);
			}
		}
		elseif($admin['Post'] === "1" and $post === "0")
		{
			self::file_delete($admin['Identified'], "post", $admin['Module_ID']);
		}
		
		/* SQL */
		$data =
		[
			"Name" => $name,
			"Identified" => $identified,
			"Get" => $get,
			"Post" => $post,
			"Visible" => $visible
		];
		Reg::db_core()->update("admin", $data, array("ID" => $id));
		
		/* Данные изменённого */
		return self::select_line_by_id($id);
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
		$admin = self::select_line_by_id($id);
		
		/* Файлы */
		if($admin['Get'] === "1")
		{
			self::file_delete($admin['Identified'], "get", $admin['Module_ID']);
		}
		
		if($admin['Post'] === "1")
		{
			self::file_delete($admin['Identified'], "post", $admin['Module_ID']);
		}
		
		/* Удалить привилегии */
		Reg::db_core()->delete("user_priv", array("Admin_ID" => $id));
		
		/* Удалить */
		Reg::db_core()->delete("admin", array("ID" => $id));
		
		/* Данные удалённого */
		return $admin;
	}
	
	/**
	 * Проверка по ID
	 * 
	 * @param int $id
	 */
	public static function is_id($id)
	{
		if(!Chf::uint($id))
		{throw new Exception_Constr("Номер у админки задан неверно. ".Chf::error());}
		
		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"admin"
WHERE 
	"ID" = $1
SQL;
		$count = Reg::db_core()->query_one($query, $id, "admin");
		if($count < 1)
		{throw new Exception_Constr("Админки с номером «{$id}» не существует.");}
	}
	
	/**
	 * Выборка строки по ID
	 * 
	 * @param int $id
	 * @return array
	 */
	public static function select_line_by_id($id)
	{
		self::is_id($id);
		
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
	"Sort"
FROM 
	"admin"
WHERE 
	"ID" = $1
SQL;
		$admin = Reg::db_core()->query_line($query, $id, "admin");
				
		return $admin;
	}
	
	/**
	 * Выборка всех по модулю
	 * 
	 * @param int $module_id
	 * @return array
	 */
	public static function select_list_by_module_id($module_id)
	{
		ZN_Module::is_id($module_id);
		
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
	"Sort"
FROM 
	"admin"
WHERE 
	"Module_ID" = $1
ORDER BY
	"Sort" ASC
SQL;
		$admin = Reg::db_core()->query_assoc($query, $module_id, "admin");
		
		return $admin;
	}
	
	/**
	 * Задать сортировку
	 * 
	 * @param int $id
	 * @param int $sort (up|down|::int)
	 */
	public static function sort($id, $sort)
	{
		$id = (int)$id;
		self::is_id($id);
		
		if(!in_array($sort, array('up','down')))
		{
			$sort = (int)$sort;
			
			$data =
			[
				"Sort" => $sort
			];
			Reg::db_core()->update("admin", $data, array("ID" => $id));
		}
		else 
		{
			$admin = self::select_line_by_id($id);
			
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
			$other = Reg::db_core()->query_assoc($query, $admin['Module_ID'], "admin");
			
			if(count($other) < 2)
			{
				throw new Exception_Constr("Необходимо хотя бы два исполнителя.");
			}

			foreach ($other as $key=>$val)
			{
				if($val['ID'] == $id)
				{break;}
			}

			if($sort == "up")
			{
				if($key == 0)
				{throw new Exception_Constr("Выше некуда.");}
				
				$id_next = $other[$key-1]['ID'];
				$sort_int = $other[$key-1]['Sort'];
				$sort_int_next = $other[$key]['Sort'];
			}
			elseif($sort == "down")
			{
				if($key == count($other)-1)
				{throw new Exception_Constr("Ниже некуда.");}
		
				$id_next = $other[$key+1]['ID'];
				$sort_int = $other[$key+1]['Sort'];
				$sort_int_next = $other[$key]['Sort'];
			}

			$data =
			[
				"Sort" => $sort_int
			];
			Reg::db_core()->update("admin", $data, array("ID" => $id));
		
			$data =
			[
				"Sort" => $sort_int_next
			];
			Reg::db_core()->update("admin", $data, array("ID" => $id_next));
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
		$module = ZN_Module::select_line_by_id($module_id);
		$path_admin = "{$module['Type']}/{$module['Identified']}/admin";
		
		if(!Reg::file_app()->is_dir($path_admin))
		{Reg::file_app()->mkdir($path_admin);}
		
		if(!Reg::file_app()->is_dir($path_admin . "/act"))
		{Reg::file_app()->mkdir($path_admin . "/act");}
		
		if($type === "get")
		{
			Reg::file_app()->put($path_admin . "/act/{$identified}.php", "<?php\n?>");
			
			if(!Reg::file_app()->is_dir($path_admin . "/html"))
			{Reg::file_app()->mkdir($path_admin . "/html");}
			
			Reg::file_app()->put($path_admin . "/html/{$identified}.html", "");
		}
		elseif ($type === "post") 
		{
			Reg::file_app()->put($path_admin . "/act/{$identified}_post.php", "<?php\n?>");
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
		$module = ZN_Module::select_line_by_id($module_id);
		$path_admin = "{$module['Type']}/{$module['Identified']}/admin";
		
		if($type === "get")
		{
			Reg::file_app()->mv
			(
				$path_admin . "/act/{$identified_old}.php",
				$path_admin . "/act/{$identified_new}.php"
			);

			Reg::file_app()->mv
			(
				$path_admin . "/html/{$identified_old}.html",
				$path_admin . "/html/{$identified_new}.html"
			);
			
		}
		elseif($type === "post")
		{
			Reg::file_app()->mv
			(
				$path_admin . "/act/{$identified_old}_post.php",
				$path_admin . "/act/{$identified_new}_post.php"
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
		$module = ZN_Module::select_line_by_id($module_id);
		$path_admin = "{$module['Type']}/{$module['Identified']}/admin";
		
		if($type === "get")
		{
			Reg::file_app()->rm($path_admin . "/act/{$identified}.php");
			Reg::file_app()->rm($path_admin . "/html/{$identified}.html");
			
		}
		elseif($type === "post")
		{
			Reg::file_app()->rm($path_admin . "/act/{$identified}_post.php");
		}
		
		/* Удалить папку если админок больше нет */
		if(Reg::file_app()->is_dir($path_admin . "/html"))
		{
			if(count(Reg::file_app()->ls($path_admin . "/html")) === 0)
			{Reg::file_app()->rm($path_admin . "/html");}
		}
		
		if(count(Reg::file_app()->ls($path_admin . "/act")) === 0)
		{
			Reg::file_app()->rm($path_admin . "/act");
			Reg::file_app()->rm($path_admin);
		}
	}
	
	/**
	 * Уникальность
	 * 
	 * @param string $name
	 * @param string $identified
	 * @param int $module_id
	 * @param int $id
	 */
	private static function _unique($name, $identified, $module_id, $id=null)
	{
		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"admin"
WHERE 
	"Name" = $1 AND 
	"Module_ID" = $2 
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		$count = Reg::db_core()->query_one($query, array($name, $module_id), "admin");
		if($count > 0)
		{Err::add("Инк с полем «Наименование» : «{$name}» уже существует.", "Name");}

		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"admin"
WHERE 
	"Identified" = $1 AND
	"Module_ID" = $2
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		$count = Reg::db_core()->query_one($query, array($identified, $module_id), "admin");
		if($count > 0)
		{Err::add("Инк с полем «Идентификатор» : «{$identified}» уже существует.", "Identified");}
	}
}
?>