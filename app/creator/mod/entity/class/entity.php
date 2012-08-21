<?php
/**
 * Сущность
 */
class ZN_Entity
{
	/**
	 * Добавить
	 * 
	 * @param string $name
	 * @param string $identified
	 * @param string $desc
	 * @param int $pack_id
	 * @return bool
	 */
	public static function add($name, $identified, $desc, $pack_id)
	{
		/* Проверка */
		if(!Chf::string($name))
		{Err::add("Наименование задано неверно. ".Chf::error(), "name");}
		
		if(!Chf::identified($identified))
		{Err::add("Идентификатор задан неверно. ".Chf::error(), "identified");}
		
		if(!empty($desc) and !Chf::text($desc))
		{Err::add("Описание задано неверно. ".Chf::error(), "desc");}
		
		ZN_Pack::is_id($pack_id);
		
		Err::exception();
		
		/* Уникальность */
		self::_unique($name, $identified, $pack_id);
		
		Err::exception();
		
		/* Выполнить SQL */
		ZN_SQL_Entity::add($identified, $pack_id);
		
		/* Добавить */
		$query = 
<<<SQL
INSERT INTO "entity"("Name", "Identified", "Desc", "Pack_ID")
VALUES ($1, $2, $3, $4)
SQL;
		Reg::db_creator()->query($query, array($name, $identified, $desc, $pack_id), "entity", true);
		
		return  true;
	}
	
	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $name
	 * @param string $identified
	 * @param string $desc
	 * @return bool 
	 */
	public static function edit($id, $name, $identified, $desc)
	{
		/* Проверка */
		self::is_id($id);
		
		if(!Chf::string($name))
		{Err::add("Наименование задано неверно. ".Chf::error(), "name");}
		
		if(!Chf::identified($identified))
		{Err::add("Идентификатор задан неверно. ".Chf::error(), "identified");}
		
		if(!empty($desc) and !Chf::text($desc))
		{Err::add("Описание задано неверно. ".Chf::error(), "desc");}
		
		Err::exception();
		
		/* Уникальность */
		$query = 
<<<SQL
SELECT "Pack_ID"
FROM "entity"
WHERE "ID" = $1
SQL;
		$pack_id = Reg::db_creator()->query_one($query, $id, "entity");
		
		self::_unique($name, $identified, $pack_id, $id);
		
		Err::exception();
		
		/* Выполнить SQL */
		ZN_SQL_Entity::edit($id, $identified);
		
		/* Редактировать */
		$query = 
<<<SQL
UPDATE "entity"
SET 
	"Name" = $1, 
	"Identified" = $2,
	"Desc" = $3
WHERE "ID" = $4
SQL;
		Reg::db_creator()->query($query, array($name, $identified, $desc, $id), "entity", true);

		return  true;
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
		
		/* Зависемости */
		$query = 
<<<SQL
SELECT "ID"
FROM "field"
WHERE "Entity_ID" = $1
SQL;
		$field = Reg::db_creator()->query_column($query, $id, "field");
		if(!empty ($field))
		{
			foreach ($field as $val)
			{ZN_Field::delete($val);}
		}
		
		/* Выполнить SQL */
		ZN_SQL_Entity::delete($id);
		
		/* Удаление */
		$query = 
<<<SQL
DELETE
FROM "entity"
WHERE "ID" = $1
SQL;
		Reg::db_creator()->query($query, $id, "entity", true);
		
		return  true;
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
		{throw new Exception_Creator("Номер сущности задан неверно. ".Chf::error());}
		
		$query = 
<<<SQL
SELECT COUNT(*) as count
FROM "entity"
WHERE "ID" = $1
SQL;
		$count = Reg::db_creator()->query_one($query, $id, "entity");
		if($count < 1)
		{throw new Exception_Creator("Сущности с номером \"{$id}\" не существует.");}
		
		return true;
	}
	
	/**
	 * Уникальность
	 * 
	 * @param string $name
	 * @param string $identified
	 * @param int $pack_id
	 * @param int $id
	 * @return bool
	 */
	private static function _unique($name, $identified, $pack_id, $id=null)
	{
		/* Наименование */
		$query = 
<<<SQL
SELECT COUNT(*) as count
FROM "entity"
WHERE "Name" = $1
AND "Pack_ID" = $2
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		
		$count = Reg::db_creator()->query_one($query, array($name, $pack_id), "entity");
		if($count > 0)
		{Err::add("Сущность с именем \"{$name}\" уже существует.", "name");}
		
		/* Идентификатор */
		$query = 
<<<SQL
SELECT COUNT(*) as count
FROM "entity"
WHERE "Identified" = $1
AND "Pack_ID" = $2
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		
		$count = Reg::db_creator()->query_one($query, array($identified, $pack_id), "entity");
		if($count > 0)
		{Err::add("Сущность с идентификатором \"{$identified}\" уже существует.", "identified");}
		
		return true;
	}
	
	/**
	 * Выборка списка сущностей по пакету
	 * 
	 * @param int $pack_id
	 * @return array
	 */
	public static function select_list_by_pack_id($pack_id)
	{
		ZN_Pack::is_id($pack_id);
		
		$query = 
<<<SQL
SELECT "ID", "Name", "Identified", "Desc", "Pack_ID"
FROM "entity"
WHERE "Pack_ID" = $1
ORDER BY "Identified" ASC
SQL;
        $entity = Reg::db_creator()->query_assoc($query, $pack_id, "entity");
		
		return $entity;
	}
	
	/**
	 * Выборка строки сущности по ID
	 * 
	 * @param int $id
	 * @return array
	 */
	public static function select_line_by_id($id)
	{
		self::is_id($id);
		
		$query = 
<<<SQL
SELECT "ID", "Name", "Identified", "Desc", "Pack_ID"
FROM "entity"
WHERE "ID" = $1
SQL;
		$entity = Reg::db_creator()->query_line($query, $id, "entity");
		
		return $entity;
	}
}
?>
