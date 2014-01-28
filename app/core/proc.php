<?php
/**
 * Процедуры
 */
class ZN_Proc
{
	/**
	 * Добавить
	 * 
	 * @param string $name
	 * @param string $identified
	 * @param enum $type (start|end)
	 * @param int $module_id
	 * @return array
	 */
	public static function add($name, $identified, $type, $module_id)
	{
		/* Проверка */
		Err::check_field($name, "string", false, "Name", "Наименование");
		Err::check_field($identified, "identified", false, "Identified", "Идентификатор");
		
		if(!in_array($type, array("start","end")))
		{Err::add("Поле «Тип» задано неверно. ".Chf::error(), "Type");}
		
		ZN_Module::is_id($module_id);
		
		Err::exception();
		
		/* Уникальность */
		self::_unique($name, $identified, $module_id);
		
		Err::exception();
		
		/* Файлы */
		$module = ZN_Module::select_line_by_id($module_id);
		
		if(!Reg::file_app()->is_dir("{$module['Type']}/{$module['Identified']}/proc_{$type}"))
		{Reg::file_app()->mkdir("{$module['Type']}/{$module['Identified']}/proc_{$type}");}
		
		Reg::file_app()->put("{$module['Type']}/{$module['Identified']}/proc_{$type}/{$identified}.php", "<?php\n\n?>");
		
		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Identified" => $identified,
			"Type" => $type,
			"Module_ID" => $module_id
		];
		$id = Reg::db_core()->insert("proc", $data, "ID");
		
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
		$proc = self::select_line_by_id($id);
		self::_unique($name, $identified, $proc['Module_ID'], $id);
		
		Err::exception();
		
		/* Файлы */
		$module = ZN_Module::select_line_by_id($proc['Module_ID']);
		Reg::file_app()->mv
		(
			"{$module['Type']}/{$module['Identified']}/proc_{$proc['Type']}/{$proc['Identified']}.php",
			"{$module['Type']}/{$module['Identified']}/proc_{$proc['Type']}/{$identified}.php"
		);
		
		/* SQL */
		$data =
		[
			"Name" => $name,
			"Identified" => $identified,
			"Active" => $active
		];
		Reg::db_core()->update("proc", $data, array("ID" => $id));
		
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
		$proc = self::select_line_by_id($id);
		$module = ZN_Module::select_line_by_id($proc['Module_ID']);
		
		/* Файлы */
		Reg::file_app()->rm("{$module['Type']}/{$module['Identified']}/proc_{$proc['Type']}/{$proc['Identified']}.php");
		
		if(count(Reg::file_app()->ls("{$module['Type']}/{$module['Identified']}/proc_{$proc['Type']}")) === 0)
		{Reg::file_app()->rm("{$module['Type']}/{$module['Identified']}/proc_{$proc['Type']}");}
		
		/* Удалить */
		Reg::db_core()->delete("proc", array("ID" => $id));
		
		/* Данные удалённого */
		return $proc;
	}
	
	/**
	 * Проверка по ID
	 * 
	 * @param int $id
	 */
	public static function is_id($id)
	{
		if(!Chf::uint($id))
		{throw new Exception_Constr("Номер у процедуры задан неверно. ".Chf::error());}
		
		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"proc"
WHERE 
	"ID" = $1
SQL;
		$count = Reg::db_core()->query_one($query, $id, "proc");
		if($count < 1)
		{throw new Exception_Constr("Процедуры с номером «{$id}» не существует.");}
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
	"Type",
	"Module_ID",
	"Active"::int
FROM 
	"proc"
WHERE 
	"ID" = $1
SQL;
		$proc = Reg::db_core()->query_line($query, $id, "proc");
		
		return $proc;
	}
	
	/**
	 * Выборка всех по модулю
	 * 
	 * @param int $module_id
	 * @param enum $type (start|end|all)
	 * @return array
	 */
	public static function select_list_by_module_id($module_id, $type="all")
	{
		ZN_Module::is_id($module_id);
		
		if(!in_array($type, array("start","end","all")))
		{throw new Exception_Constr("Тип задан неверно.");}
		
		$sql_type = "";
		if($type != "all")
		{$sql_type = "AND \"Type\" = '{$type}'";}
		
		$query =
<<<SQL
SELECT
	"ID",
	"Name",
	"Identified",
	"Type",
	"Module_ID",
	"Active"::int
FROM 
	"proc"
WHERE 
	"Module_ID" = $1 
	{$sql_type}
ORDER BY
	"Identified" ASC
SQL;
		$proc = Reg::db_core()->query_assoc($query, $module_id, "proc");
		
		return $proc;
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
	"proc"
WHERE 
	"Name" = $1 AND 
	"Module_ID" = $2 
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		$count = Reg::db_core()->query_one($query, array($name, $module_id), "proc");
		if($count > 0)
		{Err::add("Процедура с полем «Наименование» : «{$name}» уже существует.", "Name");}

		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"proc"
WHERE 
	"Identified" = $1 AND
	"Module_ID" = $2
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		$count = Reg::db_core()->query_one($query, array($identified, $module_id), "proc");
		if($count > 0)
		{Err::add("Процедура с полем «Идентификатор» : «{$identified}» уже существует.", "Identified");}
	}
}
?>