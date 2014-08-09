<?php
/**
 * Новости
 */
class News
{
	/**
	 * Добавить
	 * 
	 * @param string $date
	 * @param string $title
	 * @param string $url
	 * @param string $anons
	 * @param string $content
	 * @param string $tags
	 * @return array
	 */
	public static function add($date, $title, $url, $anons, $content, $tags)
	{
		/* Проверка */
		Err::check_field($date, "date", false, "Date", "Дата");
		Err::check_field($title, "string", false, "Title", "Заголовок");
		Err::check_field($url, "url_part", false, "Url", "Адрес");
		Err::check_field($anons, "string", true, "Anons", "Анонс");
		Err::check_field($content, "html", true, "Content", "Содержание");
		Err::check_field($tags, "tags", true, "Tags", "Теги");
		Err::exception();
		
		/* Уникальность */
		self::_unique($title, $url);
		Err::exception();
		
		/* SQL */
		$data = 
		[
			"Date" => $date,
			"Title" => $title,
			"Url" => $url,
			"Anons" => $anons,
			"Content" => $content,
			"Tags" => mb_strtolower($tags)
		];
		$id = Reg::db()->insert("news", $data, "ID");
		
		/* Данные добавленного */
		return self::select_line_by_id($id);
	}
	
	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $date
	 * @param string $title
	 * @param string $url
	 * @param string $anons
	 * @param string $content
	 * @param string $tags
	 * @return array
	 */
	public static function edit($id, $date, $title, $url, $anons, $content, $tags)
	{
		/* Проверка */
		self::is_id($id);
		Err::check_field($date, "date", false, "Date", "Дата");
		Err::check_field($title, "string", false, "Title", "Заголовок");
		Err::check_field($url, "url_part", false, "Url", "Адрес");
		Err::check_field($anons, "string", true, "Anons", "Анонс");
		Err::check_field($content, "html", true, "Content", "Содержание");
		Err::check_field($tags, "tags", true, "Tags", "Теги");
		Err::exception();
		
		/* Уникальность */
		self::_unique($title, $url, $id);
		Err::exception();
		
		/* SQL */
		$data =
		[
			"Date" => $date,
			"Title" => $title,
			"Url" => $url,
			"Anons" => $anons,
			"Content" => $content,
			"Tags" => mb_strtolower($tags),
			"Last_Modified" => "now()"
		];
		Reg::db()->update("news", $data, array("ID" => $id));
		
		/* Данные отредактированного */
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
		$news = self::select_line_by_id($id);
		
		Reg::db()->delete("news", array("ID" => $id));
		
		return $news;
	}
	
	/**
	 * Проверка по ID
	 * 
	 * @param int $id
	 */
	public static function is_id($id)
	{
		if(!Chf::uint($id))
		{throw new Exception_Admin("Номер у новости задан неверно. ".Chf::error());}
		
		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"news"
WHERE 
	"ID" = $1
SQL;
		$count = Reg::db()->query_one($query, $id, "news");
		if($count < 1)
		{throw new Exception_Admin("Новости с номером «{$id}» не существует.");}
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
	"Date", 
	"Title", 
	"Url", 
	"Anons", 
	"Content",
	"Tags",
	"Last_Modified"
FROM 
	"news"
WHERE 
	"ID" = $1
SQL;
		$news = Reg::db()->query_line($query, $id, "news");
		
		return $news;
	}
	
	/**
	 * Уникальность
	 * 
	 * @param string $title
	 * @param string $url
	 * @param int $id
	 */
	private static function _unique($title, $url, $id=0)
	{
		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"news"
WHERE 
	"Title" = $1
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		$count = Reg::db()->query_one($query, $title, "news");
		if($count > 0)
		{Err::add("Новость с полем «Заголовок» : «{$title}» уже существует.", "Title");}
		
		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"news"
WHERE 
	"Url" = $1
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		$count = Reg::db()->query_one($query, $url, "news");
		if($count > 0)
		{Err::add("Новость с полем «Адрес» : «{$url}» уже существует.", "Url");}
	}
}
?>