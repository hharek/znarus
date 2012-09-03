<?php
/**
 * Пакет
 */
class ZN_Pack
{
	/**
	 * Добавить
	 * 
	 * @param string $name
	 * @param string $identified
	 * @return bool 
	 */
	public static function add($name, $identified)
	{
		/* Поля */
		if(!Chf::string($name))
		{Err::add("Наименование задано неверно. ".Chf::error(), "name");}
		
		if(!Chf::identified($identified))
		{Err::add("Идентификатор задан неверно. ".Chf::error(), "identified");}
		
		Err::exception();
		
		/* Уникальность */
		self::_unique($name, $identified);
		
		Err::exception();
		
		/* Запрос */
		$query = 
<<<SQL
INSERT INTO "pack" ("Name", "Identified")
VALUES ($1, $2)
SQL;
		Reg::db_creator()->query($query, array($name, $identified), "pack", true);
		
		return  true;
	}
	
	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $name
	 * @param string $identified
	 * @return bool 
	 */
	public static function edit($id, $name, $identified)
	{
		/* ID-шник */
		self::is_id($id);
		
		/* Поля */
		if(!Chf::string($name))
		{Err::add("Наименование задано неверно. ".Chf::error(), "name");}
		
		if(!Chf::identified($identified))
		{Err::add("Идентификатор задан неверно. ".Chf::error(), "identified");}
		
		Err::exception();
		
		/* Уникальность */
		self::_unique($name, $identified, $id);
		
		Err::exception();
		
		/* Выполнить SQL */
		ZN_SQL_Pack::edit($id, $identified);
		
		/* Работа с кодом */
		ZN_Code_Pack::edit($id, $identified);
		
		/* Редактировать */
		$query = 
<<<SQL
UPDATE "pack"
SET 
	"Name" = $1, 
	"Identified" = $2
WHERE "ID" = $3
SQL;
		Reg::db_creator()->query($query, array($name, $identified, $id), "pack", true);
		
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
		/* ID-шник */
		self::is_id($id);
		
		/* Зависимости */
		self::_delete_entity($id);
		
		/* Удаление */
		$query = 
<<<SQL
DELETE
FROM "pack"
WHERE "ID" = $1
SQL;
		Reg::db_creator()->query($query, $id, "pack", true);
		
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
		{throw new Exception_Creator("Номер пакета задан неверно. ".Chf::error());}
		
		$query = 
<<<SQL
SELECT COUNT(*) as count
FROM "pack"
WHERE "ID" = $1
SQL;
		$count = Reg::db_creator()->query_one($query, $id, "pack");
		if($count < 1)
		{throw new Exception_Creator("Пакета с номером \"{$id}\" не существует.");}
		
		return true;
	}
	
	/**
	 * Уникальность
	 * 
	 * @param string $name
	 * @param string $identified
	 * @param int $id
	 * @return bool
	 */
	private static function _unique($name, $identified, $id=null)
	{
		/* Наименование */
		$query = 
<<<SQL
SELECT COUNT(*) as count
FROM "pack"
WHERE "Name" = $1
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		
		$count = Reg::db_creator()->query_one($query, $name, "pack");
		if($count > 0)
		{Err::add("Пакет с именем \"{$name}\" уже существует.", "name");}
		
		/* Идентификатор */
		$query = 
<<<SQL
SELECT COUNT(*) as count
FROM "pack"
WHERE "Identified" = $1
SQL;
		if(!is_null($id))
		{$query .= " AND \"ID\" != '{$id}'";}
		
		$count = Reg::db_creator()->query_one($query, $identified, "pack");
		if($count > 0)
		{Err::add("Пакет с идентификатором \"{$identified}\" уже существует.", "identified");}
		
		return  true;
	}
	
	/*------------------------------------------------------*/
	
	/**
	 * Выборка списка пакетов
	 * 
	 * @return array
	 */
	public static function select_list()
	{
		$query = 
<<<SQL
SELECT "ID", "Name", "Identified"
FROM "pack"
ORDER BY "Identified" ASC
SQL;
		$pack = Reg::db_creator()->query_assoc($query, null, "pack");
		
		return $pack;
	}
	
	/**
	 * Выборка строки по пакету
	 * 
	 * @param int $id
	 * @return array
	 */
	public static function select_line_by_id($id)
	{
		self::is_id($id);
		
		$query = 
<<<SQL
SELECT "ID", "Name", "Identified"
FROM "pack"
WHERE "ID" = $1
SQL;
		$pack = Reg::db_creator()->query_line($query, $id, "pack");
		
		return $pack;
	}
	
	/**
	 * Рекурсивная функция удаления сущности у пакета
	 * 
	 * @param int $pack_id
	 * @return boolean 
	 */
	private static function _delete_entity($id)
	{
		/* Выборка сущностей */
		$query = 
<<<SQL
SELECT "ID"
FROM "entity"
WHERE "Pack_ID" = $1
SQL;
        $entity = Reg::db_creator()->query_column($query, $id, "entity");
		
		/* Сущностей нет выходим */
		if(empty($entity))
		{return true;}
		
		/* Поиск сущностей для удаления */
		foreach ($entity as $val)
		{
			$query = 
<<<SQL
SELECT COUNT(*) as count
FROM "field"
WHERE "Entity_ID" = $1
AND "ID" IN 
(
	SELECT "Foreign_ID"
	FROM "field"
	WHERE "Foreign_ID" IS NOT NULL
	AND "Entity_ID" != $2
)
SQL;
			$count = Reg::db_creator()->query_one($query, array($val, $val), array("field","field_type"));
			if($count < 1)
			{
				ZN_Entity::delete($val);
				self::_delete_entity($id);
				break;
			}
		}
		
		return true;
	}
}
?>
