<?php
/**
 * PHP класс
 */
class ZN_Phpclass
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
		if(!Reg::file_app()->is_dir("{$module['Type']}/{$module['Identified']}/class"))
		{Reg::file_app()->mkdir("{$module['Type']}/{$module['Identified']}/class");}
		
		$code = str_replace
		(
			['{Name}','{Identified}'], 
			[$name, $identified], 
			Reg::file_app()->get("constr/tpl/phpclass.tpl")
		);
		
		Reg::file_app()->put
		(
			"{$module['Type']}/{$module['Identified']}/class/" . strtolower($identified) . ".php", 
			$code
		);
		
		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Identified" => $identified,
			"Module_ID" => $module_id
		];
		$id = Reg::db_core()->insert("phpclass", $data, "ID");
		
		/* Данные добавленного */
		return self::select_line_by_id($id);
	}
	
	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $name
	 * @param string $identified
	 * @return array
	 */
	public static function edit($id, $name, $identified)
	{
		/* Проверка */
		self::is_id($id);
		Err::check_field($name, "string", false, "Name", "Наименование");
		Err::check_field($identified, "identified", false, "Identified", "Идентификатор");
		
		Err::exception();
		
		/* Уникальность */
		$phpclass = self::select_line_by_id($id);
		self::_unique($name, $identified, $phpclass['Module_ID'], $id);
		
		Err::exception();
		
		/* Файлы */
		$module = ZN_Module::select_line_by_id($phpclass['Module_ID']);
		Reg::file_app()->mv
		(
			"{$module['Type']}/{$module['Identified']}/class/" . strtolower($phpclass['Identified']) . ".php",
			"{$module['Type']}/{$module['Identified']}/class/" . strtolower($identified) . ".php"
		);
		
		/* SQL */
		$data =
		[
			"Name" => $name,
			"Identified" => $identified
		];
		Reg::db_core()->update("phpclass", $data, array("ID" => $id));
		
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
		/* Данные */
		$phpclass = self::select_line_by_id($id);
		$module = ZN_Module::select_line_by_id($phpclass['Module_ID']);
		
		/* Файлы */
		Reg::file_app()->rm("{$module['Type']}/{$module['Identified']}/class/" . strtolower($phpclass['Identified']) . ".php");
		
		if(count(Reg::file_app()->ls("{$module['Type']}/{$module['Identified']}/class")) === 0)
		{Reg::file_app()->rm("{$module['Type']}/{$module['Identified']}/class");}
		
		/* Удалить */
		Reg::db_core()->delete("phpclass", array("ID" => $id));
		
		/* Данные удалённого */
		return $phpclass;
	}
	
	/**
	 * Проверка по ID
	 * 
	 * @param int $id
	 */
	public static function is_id($id)
	{
		if(!Chf::uint($id))
		{throw new Exception_Constr("Номер у php-класса задан неверно. ".Chf::error());}
		
		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"phpclass"
WHERE 
	"ID" = $1
SQL;
		$count = Reg::db_core()->query_one($query, $id, "phpclass");
		if($count < 1)
		{throw new Exception_Constr("Php-класса с номером «{$id}» не существует.");}
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
	"Module_ID"
FROM 
	"phpclass"
WHERE 
	"ID" = $1
SQL;
		$phpclass = Reg::db_core()->query_line($query, $id, "phpclass");
		
		return $phpclass;
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
	"Module_ID"
FROM 
	"phpclass"
WHERE 
	"Module_ID" = $1
ORDER BY
	"Identified" ASC
SQL;
		$phpclass = Reg::db_core()->query_assoc($query, $module_id, "phpclass");
		
		return $phpclass;
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
	"phpclass"
WHERE 
	"Name" = $1 AND 
	"Module_ID" = $2 
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		$count = Reg::db_core()->query_one($query, array($name, $module_id), "phpclass");
		if($count > 0)
		{Err::add("Php-класс с полем «Наименование» : «{$name}» уже существует.", "Name");}

		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"phpclass"
WHERE 
	"Identified" = $1 AND
	"Module_ID" = $2
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		$count = Reg::db_core()->query_one($query, array($identified, $module_id), "phpclass");
		if($count > 0)
		{Err::add("Php-класс с полем «Идентификатор» : «{$identified}» уже существует.", "Identified");}
	}
}
?>