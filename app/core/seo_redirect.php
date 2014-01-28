<?php
/**
 * Адреса для переадресации
 */
class ZN_Seo_Redirect
{
	/**
	 * Добавить
	 * 
	 * @param string $from
	 * @param string $to
	 * @return array
	 */
	public static function add($from, $to)
	{
		/* Проверка */
		Err::check_field($from, "url", false, "From", "Источник");
		Err::check_field($to, "url", false, "To", "Назначение");
		Err::exception();
		
		/* Уникальность */
		self::_unqiue($from);
		Err::exception();
		
		/* Добавить */
		$data = 
		[
			"From" => $from,
			"To" => $to
		];
		$id = Reg::db_core()->insert("seo_redirect", $data, "ID");
		
		/* Данные добавленного */
		return self::select_line_by_id($id);
	}
	
	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $from
	 * @param string $to
	 * @return array
	 */
	public static function edit($id, $from, $to)
	{
		/* Проверка */
		self::is_id($id);
		Err::check_field($from, "url", false, "From", "Источник");
		Err::check_field($to, "url", false, "To", "Назначение");
		Err::exception();
		
		/* Уникальность */
		self::_unqiue($from, $id);
		Err::exception();
		
		/* Редактировать */
		$data =
		[
			"From" => $from,
			"To" => $to
		];
		Reg::db_core()->update("seo_redirect", $data, array("ID" => $id));
		
		/* Данные редактируемого */
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
		$redirect = self::select_line_by_id($id);
		
		Reg::db_core()->delete("seo_redirect", array("ID" => $id));
		
		return $redirect;
	}
	
	/**
	 * Проверка по ID
	 * 
	 * @param int $id
	 * @return array
	 */
	public static function is_id($id)
	{
		if(!Chf::uint($id))
		{throw new Exception_Admin("Номер у переадресации задан неверно. ".Chf::error());}
		
		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"seo_redirect"
WHERE 
	"ID" = $1
SQL;
		$count = Reg::db_core()->query_one($query, $id, "seo_redirect");
		if($count < 1)
		{throw new Exception_Admin("Переадресации с номером «{$id}» не существует.");}
		
		return array();
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
	"From",
	"To"
FROM 
	"seo_redirect"
WHERE 
	"ID" = $1
SQL;
		$redirect = Reg::db_core()->query_line($query, $id, "seo_redirect");
		
		return $redirect;
	}
	
	/**
	 * Выборка всех по источнику
	 * 
	 * @param string $from
	 * @return array
	 */
	public static function select_list_by_from($from = "")
	{
		if(!empty($from))
		{
			if(!Chf::url($from))
			{throw new Exception_Admin("Источник задан неверно. " . Chf::error());}
		}
		
		$query =
<<<SQL
SELECT
	"ID",
	"From",
	"To"
FROM 
	"seo_redirect"
WHERE 
	"From" LIKE $1
ORDER BY
	"From" ASC
SQL;
		$redirect = Reg::db_core()->query_assoc($query, "%".$from."%", "seo_redirect");
		
		return $redirect;
	}
	
	/**
	 * Уникальность
	 * 
	 * @param string $from
	 * @param int $id
	 */
	private static function _unqiue($from, $id=null)
	{
		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"seo_redirect"
WHERE 
	"From" = $1
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		$count = Reg::db_core()->query_one($query, $from, "seo_redirect");
		if($count > 0)
		{Err::add("Источник «{$from}» уже существует.", "From");}
	}
}
?>