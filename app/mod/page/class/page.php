<?php
/**
 * Страницы
 */
class Page
{
	/**
	 * Все страницы
	 * 
	 * @var array
	 */
	private static $_page_all = array();
	
	/**
	 * Добавить
	 * 
	 * @param string $name
	 * @param string $url
	 * @param string $content
	 * @param int $parent
	 * @param string $html_identified
	 * @return array
	 */
	public static function add($name, $url, $content, $parent, $html_identified)
	{
		/* Проверка */
		Err::check_field($name, "string", false, "Name", "Наименование");
		Err::check_field($url, "url_part", false, "Url", "Урл");
		Err::check_field($content, "html", true, "Content", "Содержимое");
		
		$parent = (int)$parent;
		if(!empty($parent))
		{self::is_id($parent);}
		else
		{$parent = null;}
		
		if(trim($html_identified) !== "")
		{
			try
			{ZN_Html::is_identified($html_identified);}
			catch (Exception_Constr $e)
			{Err::add($e->getMessage(), "Html_Identified");}
		}
		
		Err::exception();
		
		/* Уникальность */
		self::_unqiue($name, $url, $parent);
		Err::exception();
		
		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Url" => $url,
			"Content" => $content,
			"Parent" => $parent,
			"Html_Identified" => $html_identified
		];
		$id = Reg::db()->insert("page", $data, "ID");
		
		/* Данные добавленного */
		return self::select_line_by_id($id);
	}
	
	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $name
	 * @param string $url
	 * @param string $content
	 * @param int $parent
	 * @param string $html_identified
	 * @return array
	 */
	public static function edit($id, $name, $url, $content, $parent, $html_identified)
	{
		/* Проверка */
		self::is_id($id);
		
		Err::check_field($name, "string", false, "Name", "Наименование");
		Err::check_field($url, "url_part", false, "Url", "Урл");
		Err::check_field($content, "html", true, "Content", "Содержимое");
		
		$parent = (int)$parent;
		if(!empty($parent))
		{self::is_id($parent);}
		else
		{$parent = null;}
		
		if(trim($html_identified) !== "")
		{
			try
			{ZN_Html::is_identified($html_identified);}
			catch (Exception_Constr $e)
			{Err::add($e->getMessage(), "Html_Identified");}
		}
		Err::exception();
		
		/* Уникальность */
		$page = self::select_line_by_id($id);
		self::_unqiue($name, $url, $page['Parent'], $id);
		
		/* SQL */
		$data =
		[
			"Name" => $name,
			"Url" => $url,
			"Content" => $content,
			"Parent" => $parent,
			"Html_Identified" => $html_identified
		];
		Reg::db()->update("page", $data, array("ID" => $id));
		
		/* Данные отредактированного */
		return self::select_line_by_id($id);;
	}
	
	/**
	 * Удалить
	 * 
	 * @param int $id
	 * @return array
	 */
	public static function delete($id)
	{
		/* Данные по удаляемой странице */
		$page = self::select_line_by_id($id);
		
		/* Страницы относящиеся к текущей странице */
		$page_child = self::select_list_by_parent($id);
		foreach ($page_child as $val)
		{
			self::delete($val['ID']);
		}
		
		/* SQL */
		Reg::db()->delete("page", array("ID" => $id));
		
		return $page;
	}
	
	/**
	 * Проверка по ID
	 * 
	 * @param int $id
	 */
	public static function is_id($id)
	{
		if(!Chf::uint($id))
		{throw new Exception_Admin("Номер у страницы задан неверно. ".Chf::error());}
		
		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"page"
WHERE 
	"ID" = $1
SQL;
		$count = Reg::db()->query_one($query, $id, "page");
		if($count < 1)
		{throw new Exception_Admin("Страницы с номером «{$id}» не существует.");}
	}
	
	/**
	 * Выборка строки по ID
	 * 
	 * @param int $id
	 * @return string
	 */
	public static function select_line_by_id($id)
	{
		self::is_id($id);
		
		$query =
<<<SQL
SELECT 
	"ID", 
	"Name",
	"Url",
	"Content",
	COALESCE("Parent", 0) as "Parent",
	"Html_Identified"
FROM 
	"page"
WHERE 
	"ID" = $1
SQL;
		$page = Reg::db()->query_line($query, $id, "page");
		
		return $page;
	}
	
	/**
	 * Выборка по корню
	 * 
	 * @param int $parent
	 * @return array
	 */
	public static function select_list_by_parent($parent)
	{
		$parent = (int)$parent;
		if($parent !== 0)
		{self::is_id($parent);}
		else 
		{$parent = 0;}
		
		$query =
<<<SQL
SELECT 
	"ID", 
	"Name",
	"Url"
FROM 
	"page"
WHERE 
	COALESCE("Parent", 0) = $1
SQL;
		$page = Reg::db()->query_assoc($query, $parent, "page");
		
		return $page;
	}
	
	/**
	 * Выборка всех страниц с подчинёнными
	 * 
	 * @param int $parent
	 */
	public static function select_list_child_by_parent($parent, $current=0)
	{
		/* Корень */
		$parent = (int)$parent;
		
		/* Текущая страница */
		$current = (int)$current;
		
		/* Все страницы */
		if(empty(self::$_page_all))
		{	
			$query =
<<<SQL
SELECT 
	"ID", 
	"Name", 
	"Url",
	COALESCE("Parent", 0) as "Parent"
FROM 
	"page"
ORDER BY 
	"Name" ASC
SQL;
			self::$_page_all = Reg::db()->query_assoc($query, null, "page");	
		}
		
		/* Перебор */
		$child = array();
		foreach (self::$_page_all as $key=>$val)
		{
			if($val['ID'] == $current)
			{continue;}
			
			if($val['Parent'] == $parent)
			{
				$child[] = 
				[
					'ID' => $val['ID'],
					'Name' => $val['Name'],
					'Url' => $val['Url'],
					'Child' => self::select_list_child_by_parent($val['ID'], $current)
				];
				
				unset(self::$_page_all[$key]);
			}
		}

		return $child;
	}
	
	/**
	 * Уникальность
	 * 
	 * @param string $name
	 * @param string $url
	 * @param int $parent
	 * @param int $id
	 */
	private static function _unqiue($name, $url, $parent, $id=null)
	{
		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"page"
WHERE 
	"Name" = $1 AND
	COALESCE("Parent", 0) = $2
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		$count = Reg::db()->query_one($query, [$name, $parent], "page");
		if($count > 0)
		{Err::add("Страница с полем «Наименование» : «{$name}» уже существует.", "Name");}
		
		$query = 
<<<SQL
SELECT 
	COUNT(*) as count
FROM 
	"page"
WHERE 
	"Url" = $1 AND
	COALESCE("Parent", 0) = $2
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		$count = Reg::db()->query_one($query, [$url, $parent], "page");
		if($count > 0)
		{Err::add("Страница с полем «Урл» : «{$url}» уже существует.", "Url");}
	}
}
?>