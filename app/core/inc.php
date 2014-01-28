<?php
/**
 * Инки
 */
class ZN_Inc
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
		
		if(!Reg::file_app()->is_dir("{$module['Type']}/{$module['Identified']}/inc"))
		{Reg::file_app()->mkdir("{$module['Type']}/{$module['Identified']}/inc");}
		
		if(!Reg::file_app()->is_dir("{$module['Type']}/{$module['Identified']}/inc/act"))
		{Reg::file_app()->mkdir("{$module['Type']}/{$module['Identified']}/inc/act");}
		
		Reg::file_app()->put("{$module['Type']}/{$module['Identified']}/inc/act/{$identified}.php", "<?php\n?>");
		
		if(!Reg::file_app()->is_dir("{$module['Type']}/{$module['Identified']}/inc/html"))
		{Reg::file_app()->mkdir("{$module['Type']}/{$module['Identified']}/inc/html");}
		
		Reg::file_app()->put("{$module['Type']}/{$module['Identified']}/inc/html/{$identified}.html", "");
		
		
		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Identified" => $identified,
			"Active" => true,
			"Module_ID" => $module_id
		];
		$id = Reg::db_core()->insert("inc", $data, "ID");
		
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
		$inc = self::select_line_by_id($id);
		self::_unique($name, $identified, $inc['Module_ID'], $id);
		
		Err::exception();
		
		/* Файлы */
		$module = ZN_Module::select_line_by_id($inc['Module_ID']);
		Reg::file_app()->mv
		(
			"{$module['Type']}/{$module['Identified']}/inc/act/{$inc['Identified']}.php",
			"{$module['Type']}/{$module['Identified']}/inc/act/{$identified}.php"
		);
		
		Reg::file_app()->mv
		(
			"{$module['Type']}/{$module['Identified']}/inc/html/{$inc['Identified']}.html",
			"{$module['Type']}/{$module['Identified']}/inc/html/{$identified}.html"
		);
		
		
		/* SQL */
		$data =
		[
			"Name" => $name,
			"Identified" => $identified,
			"Active" => $active
		];
		Reg::db_core()->update("inc", $data, array("ID" => $id));
		
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
		$inc = self::select_line_by_id($id);
		$module = ZN_Module::select_line_by_id($inc['Module_ID']);
		
		/* Удалить привязки к html */
		Reg::db_core()->delete("html_inc", array("Inc_ID" => $id));
		
		/* Файлы */
		Reg::file_app()->rm("{$module['Type']}/{$module['Identified']}/inc/act/{$inc['Identified']}.php");
		if(Reg::file_app()->is_file("{$module['Type']}/{$module['Identified']}/inc/html/{$inc['Identified']}.html"))
		{
			Reg::file_app()->rm("{$module['Type']}/{$module['Identified']}/inc/html/{$inc['Identified']}.html");
		}
		
		if(count(Reg::file_app()->ls("{$module['Type']}/{$module['Identified']}/inc/act")) === 0)
		{Reg::file_app()->rm("{$module['Type']}/{$module['Identified']}/inc/act");}
		
		if(Reg::file_app()->is_dir("{$module['Type']}/{$module['Identified']}/inc/html"))
		{
			if(count(Reg::file_app()->ls("{$module['Type']}/{$module['Identified']}/inc/html")) === 0)
			{Reg::file_app()->rm("{$module['Type']}/{$module['Identified']}/inc/html");}
		}
		
		/* Удалить */
		Reg::db_core()->delete("inc", array("ID" => $id));
		
		/* Данные удалённого */
		return $inc;
	}
	
	/**
	 * Проверка по ID
	 * 
	 * @param int $id
	 */
	public static function is_id($id)
	{
		if(!Chf::uint($id))
		{throw new Exception_Constr("Номер у инка задан неверно. ".Chf::error());}
		
		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"inc"
WHERE 
	"ID" = $1
SQL;
		$count = Reg::db_core()->query_one($query, $id, "inc");
		if($count < 1)
		{throw new Exception_Constr("Инка с номером «{$id}» не существует.");}
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
	"inc"
WHERE 
	"ID" = $1
SQL;
		$inc = Reg::db_core()->query_line($query, $id, "inc");
		
		return $inc;
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
	"inc"
WHERE 
	"Module_ID" = $1
ORDER BY
	"Identified" ASC
SQL;
		$inc = Reg::db_core()->query_assoc($query, $module_id, "inc");
		
		return $inc;
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
	"inc"
WHERE 
	"Name" = $1 AND 
	"Module_ID" = $2 
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		$count = Reg::db_core()->query_one($query, array($name, $module_id), "inc");
		if($count > 0)
		{Err::add("Инк с полем «Наименование» : «{$name}» уже существует.", "Name");}

		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"inc"
WHERE 
	"Identified" = $1 AND
	"Module_ID" = $2
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		$count = Reg::db_core()->query_one($query, array($identified, $module_id), "inc");
		if($count > 0)
		{Err::add("Инк с полем «Идентификатор» : «{$identified}» уже существует.", "Identified");}
	}
}
?>