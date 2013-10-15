<?php
/**
 * Основной шаблон
 */
class ZN_Html
{
	/**
	 * Добавить
	 * 
	 * @param string $name
	 * @param string $identified
	 * @return array
	 */
	public static function add($name, $identified)
	{
		/* Проверка */
		Err::check_field($name, "string", false, "Name", "Наименование");
		Err::check_field($identified, "identified", false, "Identified", "Идентификатор");
		
		Err::exception();
		
		/* Уникальность */
		self::_unique($name, $identified);
		
		Err::exception();
		
		/* Файл */
		Reg::file_app()->put("html/" . $identified . ".html", Reg::file_app()->get("constr/tpl/html.tpl"));
		
		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Identified" => $identified
		];
		$id = Reg::db_core()->insert("html", $data, "ID");
		
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
		self::_unique($name, $identified, $id);
		
		Err::exception();
		
		/* Файл */
		$html = self::select_line_by_id($id);
		Reg::file_app()->mv("html/{$html['Identified']}.html", "html/{$identified}.html");
		
		/* SQL */
		$data =
		[
			"Name" => $name, 
			"Identified" => $identified
		];
		Reg::db_core()->update("html", $data, array("ID" => $id));
		
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
		$html = self::select_line_by_id($id);
		
		/* Файл */
		Reg::file_app()->rm("html/{$html['Identified']}.html");
		
		/* SQL */
		Reg::db_core()->delete("html", array("ID" => $id));
		
		/* Данные удалённого */
		return $html;
	}

	/**
	 * Проверка по ID
	 * 
	 * @param int $id
	 */
	public static function is_id($id)
	{
		if(!Chf::uint($id))
		{throw new Exception_Constr("Номер у шаблона задан неверно. ".Chf::error());}
		
		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"html"
WHERE 
	"ID" = $1
SQL;
		$count = Reg::db_core()->query_one($query, $id, "html");
		if($count < 1)
		{throw new Exception_Constr("Шаблона с номером «{$id}» не существует.");}
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
	"Identified"
FROM 
	"html"
WHERE 
	"ID" = $1
SQL;
		$html = Reg::db_core()->query_line($query, $id, "html");
		
		return $html;
	}
	
	/**
	 * Выборка всех
	 * 
	 * @return array
	 */
	public static function select_list()
	{
		$query = 
<<<SQL
SELECT
	"ID",
	"Name",
	"Identified"
FROM 
	"html"
ORDER BY 
	"Identified" ASC
SQL;
		$html = Reg::db_core()->query_assoc($query, null, "html");
		
		return $html;
	}
	
	/**
	 * Уникальность
	 * 
	 * @param string $name
	 * @param string $identified
	 * @param int $id
	 */
	private static function _unique($name, $identified, $id=null)
	{
		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"html"
WHERE 
	"Name" = $1
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		$count = Reg::db_core()->query_one($query, $name, "html");
		if($count > 0)
		{Err::add("Шаблон с полем «Наименование» : «{$name}» уже существует.", "Name");}

		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"html"
WHERE 
	"Identified" = $1
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		$count = Reg::db_core()->query_one($query, $identified, "html");
		if($count > 0)
		{Err::add("Шаблон с полем «Идентификатор» : «{$identified}» уже существует.", "Identified");}
	}
}
?>