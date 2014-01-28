<?php
/**
 * Исполнители
 */
class ZN_Exe
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
		
		if(!Reg::file_app()->is_dir("{$module['Type']}/{$module['Identified']}/exe"))
		{Reg::file_app()->mkdir("{$module['Type']}/{$module['Identified']}/exe");}
		
		if(!Reg::file_app()->is_dir("{$module['Type']}/{$module['Identified']}/exe/act"))
		{Reg::file_app()->mkdir("{$module['Type']}/{$module['Identified']}/exe/act");}
		
		if(!Reg::file_app()->is_dir("{$module['Type']}/{$module['Identified']}/exe/html"))
		{Reg::file_app()->mkdir("{$module['Type']}/{$module['Identified']}/exe/html");}
		
		Reg::file_app()->put("{$module['Type']}/{$module['Identified']}/exe/act/{$identified}.php", "<?php\n?>");
		Reg::file_app()->put("{$module['Type']}/{$module['Identified']}/exe/html/{$identified}.html", "");
		
		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Identified" => $identified,
			"Active" => true,
			"Module_ID" => $module_id
		];
		$id = Reg::db_core()->insert("exe", $data, "ID");
		
		/* Данные добавленного */
		return self::select_line_by_id($id);
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
		self::is_id($id);
		Err::check_field($name, "string", false, "Name", "Наименование");
		Err::check_field($identified, "identified", false, "Identified", "Идентификатор");
		Err::check_field($active, "bool", false, "Active", "Активность");
		
		Err::exception();
		
		/* Уникальность */
		$exe = self::select_line_by_id($id);
		self::_unique($name, $identified, $exe['Module_ID'], $id);
		
		Err::exception();
		
		/* Файлы */
		$module = ZN_Module::select_line_by_id($exe['Module_ID']);
		Reg::file_app()->mv
		(
			"{$module['Type']}/{$module['Identified']}/exe/act/{$exe['Identified']}.php",
			"{$module['Type']}/{$module['Identified']}/exe/act/{$identified}.php"
		);
		Reg::file_app()->mv
		(
			"{$module['Type']}/{$module['Identified']}/exe/html/{$exe['Identified']}.html",
			"{$module['Type']}/{$module['Identified']}/exe/html/{$identified}.html"
		);
		
		/* SQL */
		$data =
		[
			"Name" => $name,
			"Identified" => $identified,
			"Active" => $active
		];
		Reg::db_core()->update("exe", $data, array("ID" => $id));
		
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
		$exe = self::select_line_by_id($id);
		$module = ZN_Module::select_line_by_id($exe['Module_ID']);
		
		/* Файлы */
		Reg::file_app()->rm("{$module['Type']}/{$module['Identified']}/exe/act/{$exe['Identified']}.php");
		Reg::file_app()->rm("{$module['Type']}/{$module['Identified']}/exe/html/{$exe['Identified']}.html");
		
		if(count(Reg::file_app()->ls("{$module['Type']}/{$module['Identified']}/exe/act")) === 0)
		{Reg::file_app()->rm("{$module['Type']}/{$module['Identified']}/exe/act");}
		
		if(count(Reg::file_app()->ls("{$module['Type']}/{$module['Identified']}/exe/html")) === 0)
		{Reg::file_app()->rm("{$module['Type']}/{$module['Identified']}/exe/html");}
		
		/* Удалить */
		Reg::db_core()->delete("exe", array("ID" => $id));
		
		/* Данные удалённого */
		return $exe;
	}
	
	/**
	 * Проверка по ID
	 * 
	 * @param int $id
	 */
	public static function is_id($id)
	{
		if(!Chf::uint($id))
		{throw new Exception_Constr("Номер у исполнителя задан неверно. ".Chf::error());}
		
		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"exe"
WHERE 
	"ID" = $1
SQL;
		$count = Reg::db_core()->query_one($query, $id, "exe");
		if($count < 1)
		{throw new Exception_Constr("Исполнителя с номером «{$id}» не существует.");}
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
	"Active"::int,
	"Module_ID"
FROM 
	"exe"
WHERE 
	"ID" = $1
SQL;
		$exe = Reg::db_core()->query_line($query, $id, "exe");
		
		return $exe;
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
	"Active"::int,
	"Module_ID"
FROM 
	"exe"
WHERE 
	"Module_ID" = $1
ORDER BY
	"Priority" ASC
SQL;
		$exe = Reg::db_core()->query_assoc($query, $module_id, "exe");
		
		return $exe;
	}
	
	/**
	 * Задать приоритет выполнения
	 * 
	 * @param int $id
	 * @param int $priority (up|down|::int)
	 */
	public static function priority($id, $priority)
	{
		$id = (int)$id;
		self::is_id($id);
		
		if(!in_array($priority, array('up','down')))
		{
			$priority = (int)$priority;
			
			$data =
			[
				"Priority" => $priority
			];
			Reg::db_core()->update("exe", $data, array("ID" => $id));
		}
		else 
		{
			$exe = self::select_line_by_id($id);
			
			$query =
<<<SQL
SELECT 
	"ID", 
	"Priority"
FROM 
	"exe"
WHERE 
	"Module_ID" = $1
ORDER BY 
	"Priority" ASC
SQL;
			$other = Reg::db_core()->query_assoc($query, $exe['Module_ID'], "exe");
			
			if(count($other) < 2)
			{
				throw new Exception_Constr("Необходимо хотя бы два исполнителя.");
			}

			foreach ($other as $key=>$val)
			{
				if($val['ID'] == $id)
				{break;}
			}

			if($priority == "up")
			{
				if($key == 0)
				{throw new Exception_Constr("Выше некуда.");}
				
				$id_next = $other[$key-1]['ID'];
				$priority_int = $other[$key-1]['Priority'];
				$priority_int_next = $other[$key]['Priority'];
			}
			elseif($priority == "down")
			{
				if($key == count($other)-1)
				{throw new Exception_Constr("Ниже некуда.");}
		
				$id_next = $other[$key+1]['ID'];
				$priority_int = $other[$key+1]['Priority'];
				$priority_int_next = $other[$key]['Priority'];
			}

			$data =
			[
				"Priority" => $priority_int
			];
			Reg::db_core()->update("exe", $data, array("ID" => $id));
		
			$data =
			[
				"Priority" => $priority_int_next
			];
			Reg::db_core()->update("exe", $data, array("ID" => $id_next));
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
	"exe"
WHERE 
	"Name" = $1 AND 
	"Module_ID" = $2 
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		$count = Reg::db_core()->query_one($query, array($name, $module_id), "exe");
		if($count > 0)
		{Err::add("Исполнитель с полем «Наименование» : «{$name}» уже существует.", "Name");}

		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"exe"
WHERE 
	"Identified" = $1 AND
	"Module_ID" = $2
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		$count = Reg::db_core()->query_one($query, array($identified, $module_id), "exe");
		if($count > 0)
		{Err::add("Исполнитель с полем «Идентификатор» : «{$identified}» уже существует.", "Identified");}
	}
}
?>