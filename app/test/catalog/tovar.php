<?php
/**
 * Товар
 */
class Catalog_Tovar
{
	/**
	 * Добавить
	 * 
	 * @param string $name
	 * @param int $cat_id
	 * @return bool
	 */
	public static function add($name, $cat_id)
	{
		/* Проверить */
		if(!Chf::string($name))
        {Err::add("Наименование задано неверно. ".Chf::error(), "name");}
		
		$cat_id = Catalog_Category::is_id($cat_id);
		
		/* Уникальность */
		self::_unique($name, $cat_id);
		
		/* Добавить */
		$query = 
<<<SQL
INSERT INTO "catalog_tovar" ("Name", "Category_ID")
VALUES ($1, $2)
SQL;
		Reg::db()->query($query, array($name, $cat_id), "catalog_tovar", true);
		
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
		$query = 
<<<SQL
SELECT "Category_ID"
FROM "catalog_tovar"
WHERE "ID" = $1
SQL;
		$cat_id = Reg::db()->query_one($query, $id, "catalog_tovar");
		self::_unique($name, $cat_id, $id);
		
		/* Редактировать */
		$query = 
<<<SQL
UPDATE "catalog_tovar"
SET 
	"Name" = $1
WHERE "ID" = $2
SQL;
		Reg::db()->query($query, array($name, $id), "catalog_tovar", true);
		
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
DELETE
FROM "catalog_tovar"
WHERE "ID" = $1
SQL;
		Reg::db()->query($query, $id, "catalog_tovar", true);
		
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
        {throw new Exception("Номер товара задан неверно. ");}
		
		$query = 
<<<SQL
SELECT COUNT(*) as count
FROM "catalog_tovar"
WHERE "ID" = $1
SQL;
		$count = Reg::db()->query_one($query, $id, "catalog_tovar");
		if($count < 1)
		{throw new Exception("Товара с номером \"{$id}\" не существует.");}
		
		return true;
	}
	
	/**
	 * Уникальность
	 * 
	 * @param string $name
	 * @param int $cat_id
	 * @param int $id
	 * @return bool
	 */
	private static function _unique($name, $cat_id, $id=null)
	{
		$query = 
<<<SQL
SELECT COUNT(*) as count
FROM "catalog_tovar"
WHERE "Name" = $1
AND "Category_ID" = $2
SQL;
		if(!is_null($id))
		{$query .= "AND \"ID\" != '{$id}'";}
		
		$count = Reg::db()->query_one($query, array($name, $cat_id), "catalog_tovar");
		if($count > 0)
		{Err::add("Товар с именем \"{$name}\" уже существует.", "name");}
		
		return true;
	}
}
?>