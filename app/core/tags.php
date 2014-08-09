<?php
/**
 * Теги
 */
class ZN_Tags
{
	/**
	 * Добавить тег
	 * 
	 * @param string $name
	 * @return int
	 */
	public static function add($name)
	{
		/* Проверка */
		$name = trim($name);
		if(!Chf::string($name))
		{ throw new Exception("Тег задан неверно."); }
		
		$name = mb_strtolower($name);
		
		/* Проверка на уникальность */
		$query = 
<<<SQL
SELECT 
	"ID",
	"Name",
	"Count"
FROM 
	"tags"
WHERE 
	"Name" = $1 
SQL;
		$tags = Reg::db_core()->query_line($query, $name);
		
		/* Добавить новый тег */
		if(empty($tags))
		{
			$data = 
			[
				"Name" => $name
			];
			$id = Reg::db_core()->insert("tags", $data, "ID");
		}
		/* Добавить счётчик */
		else
		{
			$data =
			[
				"Count" => (int)$tags['Count'] + 1
			];
			Reg::db_core()->update("tags", $data, array("ID" => $tags['ID']));
			$id = $tags['ID'];
		}
		
		return $id;
	}
	
	/**
	 * Выборка строки по имени
	 * 
	 * @param string $name
	 * @return array
	 */
	public static function select_line_by_name($name)
	{
		/* Проверка */
		$name = trim($name);
		if(!Chf::string($name))
		{ throw new Exception("Тег задан неверно."); }
		
		$name = mb_strtolower($name);
		
		/* SQL */
		$query = 
<<<SQL
SELECT 
	"ID",
	"Name",
	"Count"
FROM 
	"tags"
WHERE 
	"Name" = $1 
SQL;
		$tags = Reg::db_core()->query_line($query, $name);
		
		return $tags;
	}
	
	/**
	 * Удалить все теги
	 */
	public static function truncate()
	{
		$query = 
<<<SQL
TRUNCATE "tags"
SQL;
		Reg::db_core()->query($query);
		
		$query = 
<<<SQL
ALTER SEQUENCE "tags_seq" RESTART
SQL;
		Reg::db_core()->query($query);
	}
	
	/**
	 * Выборка всех тегов
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
	"Count"
FROM 
	"tags"
ORDER BY 
	"Name" ASC
SQL;
		$tags = Reg::db_core()->query_assoc($query, null);
		
		return $tags;
	}
}
?>