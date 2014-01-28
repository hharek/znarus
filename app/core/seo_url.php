<?php
/**
 * Адреса для продвижения
 */
class ZN_Seo_Url
{
	/**
	 * Добавить
	 * 
	 * @param string $url
	 * @param string $title
	 * @param string $keywords
	 * @param string $description
	 * @return array
	 */
	public static function add($url, $title, $keywords, $description)
	{
		/* Проверка */
		Err::check_field($url, "url", false, "Url", "Адрес");
		Err::check_field($title, "string", true, "Title", "Тег title");
		Err::check_field($keywords, "string", true, "Keywords", "Тег meta keywords");
		Err::check_field($description, "string", true, "Description", "Тег meta description");
		Err::exception();
		
		/* Уникальность */
		self::_unqiue($url);
		Err::exception();
		
		/* Добавить */
		$data = 
		[
			"Url" => $url,
			"Title" => $title,
			"Keywords" => $keywords,
			"Description" => $description
		];
		$id = Reg::db_core()->insert("seo_url", $data, "ID");
		
		/* Данные добавленного */
		return self::select_line_by_id($id);
	}
	
	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $url
	 * @param string $title
	 * @param string $keywords
	 * @param string $description
	 * @return array
	 */
	public static function edit($id, $url, $title, $keywords, $description)
	{
		/* Проверка */
		self::is_id($id);
		Err::check_field($url, "url", false, "Url", "Адрес");
		Err::check_field($title, "string", true, "Title", "Тег title");
		Err::check_field($keywords, "string", true, "Keywords", "Тег meta keywords");
		Err::check_field($description, "string", true, "Description", "Тег meta description");
		Err::exception();
		
		/* Уникальность */
		self::_unqiue($url, $id);
		Err::exception();
		
		/* Редактировать */
		$data =
		[
			"Url" => $url,
			"Title" => $title,
			"Keywords" => $keywords,
			"Description" => $description
		];
		Reg::db_core()->update("seo_url", $data, array("ID" => $id));
		
		/* Данные удалённого */
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
		$url = self::select_line_by_id($id);
		
		Reg::db_core()->delete("seo_url", array("ID" => $id));
		
		return $url;
	}
	
	/**
	 * Проверка по ID
	 * 
	 * @param int $id
	 */
	public static function is_id($id)
	{
		if(!Chf::uint($id))
		{throw new Exception_Admin("Номер у урла задан неверно. ".Chf::error());}
		
		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"seo_url"
WHERE 
	"ID" = $1
SQL;
		$count = Reg::db_core()->query_one($query, $id, "seo_url");
		if($count < 1)
		{throw new Exception_Admin("Урла с номером «{$id}» не существует.");}
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
	"Url",
	"Title",
	"Keywords",
	"Description"
FROM 
	"seo_url"
WHERE 
	"ID" = $1
SQL;
		$url = Reg::db_core()->query_line($query, $id, "seo_url");
		
		return $url;
	}
	
	/**
	 * Выборка всех по урлу
	 * 
	 * @param string $url
	 * @return array
	 */
	public static function select_list_by_url($url = "")
	{
		if(!empty($url))
		{
			if(!Chf::url($url))
			{throw new Exception_Admin("Урл задан неверно. " . Chf::error());}
		}
		
		$query =
<<<SQL
SELECT 
	"ID",
	"Url",
	"Title",
	"Keywords",
	"Description"
FROM 
	"seo_url"
WHERE 
	"Url" LIKE $1
ORDER BY
	"Url" ASC
SQL;
		$seo_url = Reg::db_core()->query_assoc($query, "%".$url."%", "seo_url");
		
		return $seo_url;
	}
	
	/**
	 * Уникальность
	 * 
	 * @param string $url
	 * @param int $id
	 */
	private static function _unqiue($url, $id=null)
	{
		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"seo_url"
WHERE 
	"Url" = $1
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		$count = Reg::db_core()->query_one($query, $url, "seo_url");
		if($count > 0)
		{Err::add("Урл «{$url}» уже существует.", "Url");}
	}
}
?>
