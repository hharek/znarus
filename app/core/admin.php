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
	 * @param int $module_id
	 * @return array
	 */
	public static function add($name, $identified, $module_id)
	{
		/* Проверка */
		Err::check_field($name, "string", false, "Name", "Наименование");
		Err::check_field($identified, "identified", false, "Identified", "Идентификатор");
		ZN_Module::is_id($module_id);
		
		Err::exception();
		
		/* Уникальность */
		self::_unique($name, $identified, $module_id);
		
		Err::exception();
		
		/* Файлы */
		$module = ZN_Module::select_line_by_id($module_id);
		
		if(!Reg::file_app()->is_dir("{$module['Type']}/{$module['Identified']}/admin"))
		{Reg::file_app()->mkdir("{$module['Type']}/{$module['Identified']}/admin");}
		
		if(!Reg::file_app()->is_dir("{$module['Type']}/{$module['Identified']}/admin/act"))
		{Reg::file_app()->mkdir("{$module['Type']}/{$module['Identified']}/admin/act");}
		
		Reg::file_app()->put("{$module['Type']}/{$module['Identified']}/admin/act/{$identified}.php", "");
		
		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Identified" => $identified,
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
	 * @param bool $visible
	 * @return array
	 */
	public static function edit($id, $name, $identified, $visible)
	{
		/* Проверка */
		self::is_id($id);
		Err::check_field($name, "string", false, "Name", "Наименование");
		Err::check_field($identified, "identified", false, "Identified", "Идентификатор");
		Err::check_field($visible, "bool", false, "Visible", "Видимость");
		
		Err::exception();
		
		/* Уникальность */
		$admin = self::select_line_by_id($id);
		self::_unique($name, $identified, $admin['Module_ID'], $id);
		
		Err::exception();
		
		/* Файлы */
		$module = ZN_Module::select_line_by_id($admin['Module_ID']);
		Reg::file_app()->mv
		(
			"{$module['Type']}/{$module['Identified']}/admin/act/{$admin['Identified']}.php",
			"{$module['Type']}/{$module['Identified']}/admin/act/{$identified}.php"
		);
		
		if(Reg::file_app()->is_file("{$module['Type']}/{$module['Identified']}/admin/html/{$admin['Identified']}.html"))
		{
			Reg::file_app()->mv
			(
				"{$module['Type']}/{$module['Identified']}/admin/html/{$admin['Identified']}.html",
				"{$module['Type']}/{$module['Identified']}/admin/html/{$identified}.html"
			);
		}
		
		/* SQL */
		$data =
		[
			"Name" => $name,
			"Identified" => $identified,
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
		$module = ZN_Module::select_line_by_id($admin['Module_ID']);
		
		/* Файлы */
		Reg::file_app()->rm("{$module['Type']}/{$module['Identified']}/admin/act/{$admin['Identified']}.php");
		if(Reg::file_app()->is_file("{$module['Type']}/{$module['Identified']}/admin/html/{$admin['Identified']}.html"))
		{
			Reg::file_app()->rm("{$module['Type']}/{$module['Identified']}/admin/html/{$admin['Identified']}.html");
		}
		
		if(count(Reg::file_app()->ls("{$module['Type']}/{$module['Identified']}/admin/act")) === 0)
		{Reg::file_app()->rm("{$module['Type']}/{$module['Identified']}/admin/act");}
		
		if(Reg::file_app()->is_dir("{$module['Type']}/{$module['Identified']}/admin/html"))
		{
			if(count(Reg::file_app()->ls("{$module['Type']}/{$module['Identified']}/admin/html")) === 0)
			{Reg::file_app()->rm("{$module['Type']}/{$module['Identified']}/admin/html");}
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