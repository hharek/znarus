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
		$pack = ZN_Pack::select_line_by_id($pack_id);
		$table = ZN_SQL_Entity::get_table_name($pack['Identified'], $identified);
		
		Err::exception();
		
		/* Уникальность */
		self::_unique($name, $identified, $pack_id);
		
		Err::exception();
		
		/* Выполнить SQL */
		Reg::db()->multi_query(ZN_SQL_Entity::add($identified, $pack_id));
		
		/* Работа с кодом */
		ZN_Code_Entity::add($name, $identified, $pack_id);
		$md5_file = md5(Reg::file_app()->get("constr/".ZN_Code_Entity::get_file_name($pack['Identified'], $identified).".php"));
		
		/* Добавить */
		$query = 
<<<SQL
INSERT INTO "entity"("Name", "Identified", "Table", "Desc", "MD5_File", "Pack_ID")
VALUES ($1, $2, $3, $4, $5, $6)
SQL;
		Reg::db_creator()->query($query, array($name, $identified, $table, $desc, $md5_file, $pack_id), "entity", true);
		
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
		
		$query = 
<<<SQL
SELECT "Pack_ID"
FROM "entity"
WHERE "ID" = $1
SQL;
		$pack_id = Reg::db_creator()->query_one($query, $id, "entity");
		$pack = ZN_Pack::select_line_by_id($pack_id);
		
		$table = ZN_SQL_Entity::get_table_name($pack['Identified'], $identified);
		
		Err::exception();
		
		/* Уникальность */
		self::_unique($name, $identified, $pack_id, $id);
		
		Err::exception();
		
		/* Выполнить SQL */
		$sql_edit = ZN_SQL_Entity::edit($id, $identified);
		if(mb_strlen($sql_edit, "UTF-8") > 0)
		{
			Reg::db()->multi_query($sql_edit);
			ZN_Entity::set_table($id, $table);
		}
		
		/* Работа с кодом */
		ZN_Code_Entity::edit($id, $name, ZN_Code_Entity::get_class_name($pack['Identified'], $identified), ZN_Code_Entity::get_file_name($pack['Identified'], $identified));
		
		/* Редактировать */
		$query = 
<<<SQL
UPDATE "entity"
SET 
	"Name" = $1, 
	"Identified" = $2,
	"Table" = $3,
	"Desc" = $4
WHERE "ID" = $5
SQL;
		Reg::db_creator()->query($query, array($name, $identified, $table, $desc, $id), "entity", true);

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
		
		/* Нельзя удалить сущность на которую ссылаются */
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
)
SQL;
		$count = Reg::db_creator()->query_one($query, $id, array("field","field_type"));
		if($count > 0)
		{
			throw new Exception_Creator("Нельзя удалить сущность на которую ссылаются.");
		}
		
		/* Удалить поля */
		$query = 
<<<SQL
SELECT "ID"
FROM "field"
WHERE "Entity_ID" = $1
ORDER BY "ID" DESC
SQL;
		$field = Reg::db_creator()->query_column($query, $id, "field");
		if(!empty ($field))
		{
			foreach ($field as $val)
			{ZN_Field::delete($val);}
		}
		
		/* Выполнить SQL */
		Reg::db()->multi_query(ZN_SQL_Entity::delete($id));
		
		/* Работа с кодом */
		ZN_Code_Entity::delete($id);
		
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
SELECT "ID", "Name", "Identified", "Table", "Desc", "Pack_ID"
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
SELECT "ID", "Name", "Identified", "Table", "Desc", "Pack_ID"
FROM "entity"
WHERE "ID" = $1
SQL;
		$entity = Reg::db_creator()->query_line($query, $id, "entity");
		
		return $entity;
	}
	
	/**
	 * Назначить новое наименование для таблицы
	 * 
	 * @param int $id
	 * @param string $table
	 * @return boolean 
	 */
	public static function set_table($id, $table)
	{
		self::is_id($id);
		
		$query = 
<<<SQL
UPDATE "entity"
SET 
	"Table" = $1
WHERE "ID" = $2
SQL;
		Reg::db_creator()->query($query, array($table, $id), "entity", true);
		
		return true;
	}
	
	/**
	 * Получить столбец ID у таблицы
	 * 
	 * @param int $id
	 * @return bool
	 */
	public static function get_field_id($id)
	{
		$query = 
<<<SQL
SELECT "f"."ID", "f"."Identified", "f"."Name"
FROM "field" as "f", "field_type" as "t"
WHERE "f"."Entity_ID" = $1
AND "f"."Type_ID" = "t"."ID"
AND "t"."Identified" = 'id'
SQL;
		$field_id = Reg::db_creator()->query_line($query, $id, array('field','field_type'));
		
		return $field_id;
	}
}
?>
