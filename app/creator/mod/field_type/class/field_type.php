<?php
/**
 * Типы полей
 */
class ZN_Field_Type
{
	/**
	 * Добавить
	 * 
	 * @param string $identified
	 * @return bool
	 */
	public static function add($identified, $desc)
	{
		/* Проверка */
		if(!Chf::identified($identified))
		{Err::add("Наименование задано неверно. ".Chf::error(), "identified");}
		$identified = mb_strtolower($identified, "UTF-8");
		
		if(!Chf::string($desc))
		{Err::add("Описание задано неверно. ".Chf::error(), "desc");}
		
		Err::exception();
		
		/* Уникальность */
		self::_unique($identified);
		
		Err::exception();
		
		/* Добавить */
		$query = 
<<<SQL
INSERT INTO "field_type" ("Identified", "Desc")
VALUES ($1, $2)
SQL;
		Reg::db_creator()->query($query, array($identified, $desc), "field_type", true);
		
		return true;
	}
	
	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $identified
	 * @return bool
	 */
	public static function edit($id, $identified, $desc)
	{
		/* Проверка */
		self::is_id($id);
		
		if(!Chf::identified($identified))
		{Err::add("Наименование задано неверно. ".Chf::error(), "identified");}
		$identified = mb_strtolower($identified, "UTF-8");
		
		if(!Chf::string($desc))
		{Err::add("Описание задано неверно. ".Chf::error(), "desc");}
		
		Err::exception();
		
		/* Уникальность */
		self::_unique($identified, $id);
		
		Err::exception();
		
		/* Редактировать */
		$query = 
<<<SQL
UPDATE "field_type"
SET 
	"Identified" = $1, 
	"Desc" = $2
WHERE "ID" = $3
SQL;
		Reg::db_creator()->query($query, array($identified, $desc, $id), "field_type", true);
		
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
		/* Проверка */
		self::is_id($id);
		
		/* Зависимости */
		$query = 
<<<SQL
SELECT COUNT(*) as count
FROM "field"
WHERE "Type_ID" = $1
SQL;
		$count = Reg::db_creator()->query_one($query, $id, "field");
		if($count > 0)
		{throw new Exception_Creator("Не возможно удалить, т.к. существуют поля с этим типом.");}
		
		/* Удалить */
		$query = 
<<<SQL
DELETE
FROM "field_type"
WHERE "ID" = $1
SQL;
		Reg::db_creator()->query($query, $id, "field_type", true);
		
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
		{throw new Exception_Creator("Номер типа поля задан неверно. ".Chf::error());}
		
		$query = 
<<<SQL
SELECT COUNT(*) as count
FROM "field_type"
WHERE "ID" = $1
SQL;
		$count = Reg::db_creator()->query_one($query, $id, "field_type");
		if($count < 1)
		{throw new Exception_Creator("Типа поля с номером \"{$id}\" не существует.");}
		
		return true;
	}
	
	/**
	 * Уникальность
	 * 
	 * @param string $identified
	 * @param int $id
	 * @return bool
	 */
	private static function _unique($identified, $id=null)
	{
		/* Наименование */
		$query = 
<<<SQL
SELECT COUNT(*) as count
FROM "field_type"
WHERE "Identified" = $1
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		
		$count = Reg::db_creator()->query_one($query, $identified, "field_type");
		if($count > 0)
		{Err::add("Тип поля с идентификатором \"{$identified}\" уже существует.", "identified");}
		
		return true;
	}
	
	/*------------------------------------------*/
	
	/**
	 * Выборка списка типов полей
	 * 
	 * @return array
	 */
	public static function select_list()
	{
		$query = 
<<<SQL
SELECT "ID", "Identified", "Desc"
FROM "field_type"
ORDER BY "Identified" ASC 
SQL;
        $type = Reg::db_creator()->query_assoc($query, null, "field_type");
		
		return $type;
	}
	
	/**
	 * Выборка строки по id
	 * 
	 * @param int $id
	 * @return array
	 */
	public static function select_line_by_id($id)
	{
		self::is_id($id);
		
		$query = 
<<<SQL
SELECT "ID", "Identified", "Desc"
FROM "field_type"
WHERE "ID" = $1
SQL;
		$type = Reg::db_creator()->query_line($query, $id, "field_type");
		
		return $type;
	}
	
	/**
	 * Получить наименование типа по ID
	 * 
	 * @param int $id
	 * @return string
	 */
	public static function get_type($id)
	{
		self::is_id($id);
		
		$query = 
<<<SQL
SELECT "Identified"
FROM "field_type"
WHERE "ID" = $1
SQL;
		$type = Reg::db_creator()->query_one($query, $id, "field_type");
		
		return $type;
	}
}
?>
