<?php
/**
 * Статьи
 */
class Articles
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
		$id = Reg::db()->insert("articles", $data, "ID");
		
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
		Reg::db()->update("articles", $data, array("ID" => $id));
		
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
		$articles = self::select_line_by_id($id);
		
		Reg::db()->delete("articles", array("ID" => $id));
		
		return $articles;
	}
	
	/**
	 * Проверка по ID
	 * 
	 * @param int $id
	 */
	public static function is_id($id)
	{
		if(!Chf::uint($id))
		{throw new Exception_Admin("Номер у статьи задан неверно. ".Chf::error());}
		
		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"articles"
WHERE 
	"ID" = $1
SQL;
		$count = Reg::db()->query_one($query, $id, "articles");
		if($count < 1)
		{throw new Exception_Admin("Статьи с номером «{$id}» не существует.");}
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
	"articles"
WHERE 
	"ID" = $1
SQL;
		$articles = Reg::db()->query_line($query, $id, "articles");
		
		return $articles;
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
	"articles"
WHERE 
	"Title" = $1
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		$count = Reg::db()->query_one($query, $title, "articles");
		if($count > 0)
		{Err::add("Статья с полем «Заголовок» : «{$title}» уже существует.", "Title");}
		
		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"articles"
WHERE 
	"Url" = $1
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		$count = Reg::db()->query_one($query, $url, "articles");
		if($count > 0)
		{Err::add("Статья с полем «Адрес» : «{$url}» уже существует.", "Url");}
	}
}
?>