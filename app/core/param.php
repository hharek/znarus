<?php
/**
 * Параметры
 */
class ZN_Param
{
	/**
	 * Добавить
	 * 
	 * @param string $name
	 * @param string $identified
	 * @param string $type (string|int|bool)
	 * @param string $value
	 * @param int $module_id
	 * @return array
	 */
	public static function add($name, $identified, $type, $value, $module_id)
	{
		/* Проверка */
		Err::check_field($name, "string", false, "Name", "Наименование");
		Err::check_field($identified, "identified", false, "Identified", "Идентификатор");
		
		if(!in_array($type, array("string","int","bool")))
		{Err::add("Поле «Тип» задано неверно. Допустимые значения: string ,int, bool.", "Type");}
		
		Err::check_field($value, $type, true, "Value", "Значение");
		
		$module_id = (int)$module_id;
		if(!empty($module_id))
		{ZN_Module::is_id($module_id);}
		else
		{$module_id = null;}
		
		Err::exception();
		
		/* Уникальность */
		self::_unique($name, $identified, $module_id);
		
		Err::exception();
		
		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Identified" => $identified,
			"Type" => $type,
			"Value" => $value,
			"Module_ID" => $module_id
		];
		$id = Reg::db_core()->insert("param", $data, "ID");
		
		/* Данные добавленного */
		return self::select_line_by_id($id);
	}
	
	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $name
	 * @param string $identified
	 * @param string $type
	 * @param string $value
	 * @return array
	 */
	public static function edit($id, $name, $identified, $type, $value)
	{
		/* Проверка */
		self::is_id($id);
		
		Err::check_field($name, "string", false, "Name", "Наименование");
		Err::check_field($identified, "identified", false, "Identified", "Идентификатор");
		
		if(!in_array($type, array("string","int","bool")))
		{Err::add("Поле «Тип» задано неверно. Допустимые значения: string ,int, bool.", "Type");}
		
		Err::check_field($value, $type, true, "Value", "Значение");
		
		Err::exception();
		
		/* Уникальность */
		$param = self::select_line_by_id($id);
		self::_unique($name, $identified, $param['Module_ID'], $id);
		
		Err::exception();
		
		/* SQL */
		$data =
		[
			"Name" => $name,
			"Identified" => $identified,
			"Type" => $type,
			"Value" => $value
		];
		Reg::db_core()->update("param", $data, array("ID" => $id));
		
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
		$param = self::select_line_by_id($id);
		
		/* Удалить */
		Reg::db_core()->delete("param", array("ID" => $id));
		
		/* Данные удалённого */
		return $param;
	}
	
	/**
	 * Проверка по ID
	 * 
	 * @param int $id
	 */
	public static function is_id($id)
	{
		if(!Chf::uint($id))
		{throw new Exception_Constr("Номер у параметра задан неверно. ".Chf::error());}
		
		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"param"
WHERE 
	"ID" = $1
SQL;
		$count = Reg::db_core()->query_one($query, $id, "param");
		if($count < 1)
		{throw new Exception_Constr("Параметра с номером «{$id}» не существует.");}
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
	"Value",
	"Module_ID"
FROM 
	"param"
WHERE 
	"ID" = $1
SQL;
		$param = Reg::db_core()->query_line($query, $id, "param");
		
		return $param;
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
	"Type",
	"Value",
	"Module_ID"
FROM 
	"param"
WHERE 
	"Module_ID" = $1
ORDER BY
	"Identified" ASC
SQL;
		$param = Reg::db_core()->query_assoc($query, $module_id, "param");
		
		return $param;
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
	"param"
WHERE 
	"Name" = $1 AND 
	"Module_ID" = $2 
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		$count = Reg::db_core()->query_one($query, array($name, $module_id), "param");
		if($count > 0)
		{Err::add("Параметр с полем «Наименование» : «{$name}» уже существует.", "Name");}

		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"param"
WHERE 
	"Identified" = $1 AND
	"Module_ID" = $2
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		$count = Reg::db_core()->query_one($query, array($identified, $module_id), "param");
		if($count > 0)
		{Err::add("Параметр с полем «Идентификатор» : «{$identified}» уже существует.", "Identified");}
	}
}
?>