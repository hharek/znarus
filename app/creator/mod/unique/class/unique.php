<?php
/**
 * Уникальные ключи
 */
class ZN_Unique
{
	/**
	 * Добавить
	 * 
	 * @param array $field_id_ar
	 * @param int $entity_id
	 * @return boolean 
	 */
	public static function add($field_id_ar, $entity_id)
	{
		/* Проверка */
		if(empty($field_id_ar))
		{throw new Exception_Creator("Необходимо указать минимум одно поле.");}
		asort($field_id_ar);
		
		ZN_Entity::is_id($entity_id);
		
		foreach ($field_id_ar as $val)
		{
			$query = 
<<<SQL
SELECT COUNT(*) as count
FROM "field"
WHERE "Entity_ID" = $1
AND "ID" = $2
SQL;
			$count = Reg::db_creator()->query_one($query, array($entity_id, $val), "field");
			if($count < 1)
			{throw new Exception_Creator("Поля с номером \"{$val}\" не существует.");}
			
			$field = ZN_Field::select_line_by_id($val);
			$field['type'] = ZN_Field_Type::get_type($field['Type_ID']);
			if(in_array($field['type'], array("id","sort","enum")))
			{throw new Exception_Creator("Нельзя добавить в уникальный ключ поля типа \"id\",\"sort\",\"enum\".");}
		}
		
		/* Уникальность */
		$field_ar_str = "'".implode("','", $field_id_ar)."'";
		$query = 
<<<SQL
SELECT "Unique_ID"
FROM "unique_field"
WHERE "Field_ID" IN ({$field_ar_str})
AND "Unique_ID" IN
(
	SELECT "Unique_ID"
	FROM "unique_field"
	GROUP BY "Unique_ID"
	HAVING COUNT(*) = $1
)
GROUP BY "Unique_ID"
HAVING COUNT(*) = $2
SQL;
		$unique_id_by_field_ar = Reg::db_creator()->query_one($query, array(count($field_id_ar), count($field_id_ar)), "unique_field");
		if(!empty($unique_id_by_field_ar))
		{
			throw new Exception_Creator("Уникальный ключ с полями {$field_ar_str} уже существует.");
		}
		
		/* Выполнить SQL */
		Reg::db()->multi_query(ZN_SQL_Constraint::unique_add($field_id_ar, $entity_id));
		
		/* Ключ */
		$query = 
<<<SQL
INSERT INTO "unique" ("Entity_ID") 
VALUES ($1)
SQL;
		Reg::db_creator()->query($query, $entity_id, "unique", true);
		
		/* Прикрепим поля */
		foreach ($field_id_ar as $val)
		{
			$query = 
<<<SQL
INSERT INTO "unique_field"("Unique_ID","Field_ID") 
VALUES (currval('unique_seq'), $1)
SQL;
			Reg::db_creator()->query($query, $val, "unique_field", true);
		}
		
		
		
		return true;
	}
	
	/**
	 * Удалить
	 * 
	 * @param int $id
	 * @return boolean 
	 */
	public static function delete($id)
	{
		self::is_id($id);
		
		/* Выполнить SQL */
		Reg::db()->multi_query(ZN_SQL_Constraint::unique_delete($id));
		
		/* Удалить зависемости */
		$query = 
<<<SQL
DELETE
FROM "unique_field"
WHERE "Unique_ID" = $1
SQL;
		Reg::db_creator()->query($query, $id, "unique_field", true);
		
		/* Удаление ключа */
		$query = 
<<<SQL
DELETE
FROM "unique"
WHERE "ID" = $1
SQL;
		Reg::db_creator()->query($query, $id, "unique", true);	
		
		return true;
	}
	
	/**
	 * Проверка на существование
	 * 
	 * @param int $id
	 * @return boolean 
	 */
	public static function is_id($id)
	{
		if(!Chf::uint($id))
		{throw new Exception_Creator("Номер уникального ключа задан неверно. ".Chf::error());}
		
		$query = 
<<<SQL
SELECT COUNT(*) as count
FROM "unique"
WHERE "ID" = $1
SQL;
		$count = Reg::db_creator()->query_one($query, $id, "unique");
		if($count < 1)
		{throw new Exception_Creator("Уникального ключа с номером \"{$id}\" не существует.");}
		
		return true;
	}
	
	/**
	 * Выборка всех ключей по сущности
	 * 
	 * @param int $entity_id
	 * @return array 
	 */
	public static function select_list_by_entity_id($entity_id)
	{
		ZN_Entity::is_id($entity_id);
		
		$query = 
<<<SQL
SELECT "ID"
FROM "unique"
WHERE "Entity_ID" = $1
SQL;
		$unique_ar = Reg::db_creator()->query_column($query, $entity_id, "unique");
		
		$unique = array();
		if(!empty($unique_ar))
		{
			foreach ($unique_ar as $val)
			{
				$unique[] = array
				(
					"id" => $val, 
					"name" => self::get_name($val),
					"field" => self::select_list_field_by_unique($val)
				);
			}
		}
		
		return $unique;
	}
	
	/**
	 * Выборка полей по уникальному ключу
	 * 
	 * @param int $id
	 * @return array 
	 */
	public static function select_list_field_by_unique($id)
	{
		self::is_id($id);
		
		$query = 
<<<SQL
SELECT "f"."ID", "f"."Name", "f"."Identified"
FROM "unique_field" as "uf", "field" as "f"
WHERE "uf"."Unique_ID" = $1
AND "uf"."Field_ID" = "f"."ID"
ORDER BY "f"."ID" ASC
SQL;
        $field = Reg::db_creator()->query_assoc($query, $id, array('unique_field','field'));
		
		return $field;
	}
	
	/**
	 * Выборка id полей по уникальному ключу
	 * 
	 * @param int $id
	 * @return array 
	 */
	public static function select_column_field_by_unique($id)
	{
		self::is_id($id);
		
		$query = 
<<<SQL
SELECT "f"."ID"
FROM "unique_field" as "uf", "field" as "f"
WHERE "uf"."Unique_ID" = $1
AND "uf"."Field_ID" = "f"."ID"
ORDER BY "f"."ID" ASC
SQL;
        $field = Reg::db_creator()->query_column($query, $id, array('unique_field','field'));
		
		return $field;
	}
	
	/**
	 * Выборка данных по уникальному ключу
	 * 
	 * @param int $id
	 * @return int
	 */
	public static function select_entity_id_by_id($id)
	{
		self::is_id($id);
		
		$query = 
<<<SQL
SELECT "Entity_ID"
FROM "unique"
WHERE "ID" = $1
SQL;
		$entity_id = Reg::db_creator()->query_one($query, $id, "unique");
		
		return $entity_id;
	}
	
	/**
	 * Выборка столбца по id поля
	 * 
	 * @param int $field_id
	 * @return array
	 */
	public static function select_column_by_field_id($field_id)
	{
		$query = 
<<<SQL
SELECT "Unique_ID"
FROM "unique_field"
WHERE "Field_ID" = $1
SQL;
		$unique = Reg::db_creator()->query_column($query, $field_id, "unique_field");
		
		return $unique;
	}
	
	/**
	 * Получить имя уникального ключа
	 * 
	 * @param int $id
	 * @return boolean 
	 */
	public static function get_name($id)
	{
		self::is_id($id);
		
		$query = 
<<<SQL
SELECT "Entity_ID"
FROM "unique"
WHERE "ID" = $1
SQL;
		$entity_id = Reg::db_creator()->query_one($query, $id, "unique");
		$entity = ZN_Entity::select_line_by_id($entity_id);
		
		$field = self::select_list_field_by_unique($id);
		$name = $entity['Table']."_UN";
		foreach ($field as $val)
		{
			$name .= "_".$val['Identified'];
		}
		
		return $name;
	}
}
?>
