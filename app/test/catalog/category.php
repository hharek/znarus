<?php 
/**
 * Категория
 */
class Catalog_Category
{
	/**
	 * Добавить
	 * 
	 * @param string $name
	 * @return bool
	 */
	public static function add($name)
	{
		/* Проверка */
		if(!Chf::string($name))
        {Err::add("Наименование задано неверно.".Chf::error(), "name");}
		
		/* Уникальность */
		self::_unique($name);
		Err::exception();
		
		/* Добавить */
		$query = 
<<<SQL
INSERT INTO catalog_category("Name")
VALUES ($1)
SQL;
		Reg::db()->query($query, $name, "catalog_category", true);
		
		return true;
	}
	
	/**
	 * Редактировать 
	 * 
	 * @param int $id
	 * @param string $name
	 * @return bool
	 */
	public static function edit($id, $name)
	{
		/* Проверка */
		self::is_id($id);
		
		if(!Chf::string($name))
        {Err::add("Наименование задано неверно. ".Chf::error(), "name");}
		
		/* Уникальность */
		self::_unique($name, $id);
		
		/* Редактировать */
		$query = 
<<<SQL
UPDATE "catalog_category"
SET 
	"Name" = $1
WHERE "ID" = $2
SQL;
		Reg::db()->query($query, array($name, $id), "catalog_category", true);
		
		return true;
	}
	
	/**
	 * Удалить
	 * 
	 * @param int $id
	 * @return bool
	 */
	public static function delete($id)
	{
		self::is_id($id);
		
		$query = 
<<<SQL
SELECT "ID"
FROM "catalog_tovar"
WHERE "Category_ID" = $1
SQL;
        $column = Reg::db()->query_assoc($query, $id, "catalog_tovar");
		if(!empty ($column))
		{
			foreach ($column as $val)
			{Catalog_Tovar::delete($val);}
		}
		
		$query = 
<<<SQL
DELETE
FROM "catalog_category"
WHERE "ID" = $1
SQL;
		Reg::db()->query($query, $id, "catalog_category", true);
		
		return true;
	}
	
	/**
	 * Проверка на существование
	 * 
	 * @param int $id
	 * @return bool
	 */
	public static function is_id($id)
	{
		if(!Chf::uint($id))
        {throw new Exception("Номер сущности задан неверно. ".Chf::error());}
		
		$query = 
<<<SQL
SELECT COUNT(*) as count
FROM "catalog_category"
WHERE "ID" = $1
SQL;
		$count = Reg::db()->query_one($query, $id, "catalog_category");
		if($count < 1)
		{throw new Exception("Категории с номером \"{$id}\" не существует.");}
		
		return true;
	}
	
	/**
	 * Уникальность
	 * 
	 * @param string $name
	 * @param int $id
	 * @return bool
	 */
	private static function _unique($name, $id=null)
	{
		/* Наименование */
		$query = 
<<<SQL
SELECT COUNT(*) as count
FROM "catalog_category"
WHERE "Name" = $1
SQL;
		if(!is_null($id))
		{$query .= "AND \"ID\" != '{$id}'";}
		
		$count = Reg::db()->query_one($query, $name, "catalog_category");
		if($count > 0)
		{Err::add("Категория с именем \"{$name}\" уже существует.", "name");}
		
		return true;
	}
}
?>