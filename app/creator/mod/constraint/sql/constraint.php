<?php
/**
 * SQL для сущностей 
 */
class ZN_SQL_Constraint
{
	/**
	 * Добавить первичный ключ
	 * 
	 * @param int $id_identified
	 * @param int $entity_id
	 * @return string 
	 */
	public static function primary_add($id_identified, $entity_id)
	{
		$entity = ZN_Entity::select_line_by_id($entity_id);
		$constraint_name = self::primary_get_name($entity['Table']);
		$sql = 
<<<SQL
ALTER TABLE "{$entity['Table']}" 
	ADD CONSTRAINT "{$constraint_name}" PRIMARY KEY ("{$id_identified}");
SQL;
		
		return $sql;
	}
	
	/**
	 * Переименовать первичный ключ
	 * 
	 * @param int $field_id
	 * @param string $name
	 * @return string 
	 */
	public static function primary_rename($field_id, $name)
	{
		$sql = "";
		$field = ZN_Field::select_line_by_id($field_id);
		$entity = ZN_Entity::select_line_by_id($field['Entity_ID']);
		
		/* Ищем внешнее ключи на ID */
		$query = 
<<<SQL
SELECT "ID"
FROM "field"
WHERE "Foreign_ID" = $1
SQL;
		$foreign = Reg::db_creator()->query_column($query, array($field['ID']), "field");
		
		$sql_foreign_delete = $sql_foreign_add = "";
		foreach ($foreign as $val)
		{
			$ref_field = ZN_Field::select_line_by_id($val);
			$ref_entity = ZN_Entity::select_line_by_id($ref_field['Entity_ID']);
			$ref_constraint_name = self::foreign_get_name($ref_entity['Table'], $ref_field['Identified']);
			
			$sql_foreign_delete .= 
<<<SQL
\nALTER TABLE "{$ref_entity['Table']}"
	DROP CONSTRAINT "{$ref_constraint_name}";
SQL;
	
			$sql_foreign_add .= 
<<<SQL
\nALTER TABLE "{$ref_entity['Table']}"
	ADD CONSTRAINT "{$ref_constraint_name}" FOREIGN KEY("{$ref_field['Identified']}")
		REFERENCES "{$entity['Table']}"("{$field['Identified']}");
SQL;
		}
		
		/* Удаляем внешние ключи */
		$sql .= $sql_foreign_delete;
		
		/* Удаляем старое ограничение и создаём новое с новым именем */
		$constraint_name = self::primary_get_name($entity['Table']);
		$sql .= 
<<<SQL
\n\nALTER TABLE "{$entity['Table']}"
	DROP CONSTRAINT "{$constraint_name}";
ALTER TABLE "{$entity['Table']}"
	ADD CONSTRAINT "{$name}" PRIMARY KEY("{$field['Identified']}");
SQL;
		
		/* Создаём внешие ключи */
		$sql .= "\n".$sql_foreign_add;
	
		return $sql;
	}
	
	/**
	 * Получить наименование ограничения для первичного ключа
	 * 
	 * @param string $table
	 * @return string 
	 */
	public static function primary_get_name($table)
	{
		return $table."_PK";
	}
	
	/**
	 * Добавить внешний ключ
	 * 
	 * @param string $identified
	 * @param int $foreign_id
	 * @param int $entity_id
	 * @return string 
	 */
	public static function foreign_add($identified, $foreign_id, $entity_id)
	{
		$entity = ZN_Entity::select_line_by_id($entity_id);
		$constraint_name = self::foreign_get_name($entity['Table'], $identified);
		
		$ref_field = ZN_Field::select_line_by_id($foreign_id);
		$ref_entity = ZN_Entity::select_line_by_id($ref_field['Entity_ID']);
				
		$sql = 
<<<SQL
ALTER TABLE "{$entity['Table']}"
	ADD "{$identified}" int NULL,
	ADD CONSTRAINT "{$constraint_name}" FOREIGN KEY ("{$identified}") 
		REFERENCES "{$ref_entity['Table']}"("{$ref_field['Identified']}");
SQL;
		
		return $sql;
	}
	
	/**
	 * Переименовать внешний ключ
	 * 
	 * @param int $field_id
	 * @param string $name
	 * @return string 
	 */
	public static function foreign_rename($field_id, $name)
	{
		$sql = "";
		$field = ZN_Field::select_line_by_id($field_id);
		$entity = ZN_Entity::select_line_by_id($field['Entity_ID']);
		$constraint_name = self::foreign_get_name($entity['Table'], $field['Identified']);
		
		/* Удаление */
		$sql .= 
<<<SQL
\nALTER TABLE "{$entity['Table']}"
	DROP CONSTRAINT "{$constraint_name}";
SQL;
	
		/* Создание */
		$ref_field = ZN_Field::select_line_by_id($field['Foreign_ID']);
		$ref_entity = ZN_Entity::select_line_by_id($ref_field['Entity_ID']);
		$sql .= 
<<<SQL
\nALTER TABLE "{$entity['Table']}"
	ADD CONSTRAINT "{$name}" FOREIGN KEY("{$field['Identified']}")
		REFERENCES "{$ref_entity['Table']}"("{$ref_field['Identified']}");
SQL;
		
		return $sql;
	}
	
	/**
	 * Получить наименование ограничения для внешнего ключа
	 * 
	 * @param string $table
	 * @param string $identified
	 * @return string 
	 */
	public static function foreign_get_name($table, $identified)
	{
		return "{$table}_FK_{$identified}";
	}
	
	/**
	 * Добавить уникальный ключ
	 * 
	 * @param array $field_id_ar
	 * @param int $entity_id
	 * @return string 
	 */
	public static function unique_add($field_id_ar, $entity_id)
	{
		$entity = ZN_Entity::select_line_by_id($entity_id);
		$constraint_name = self::unique_get_name($field_id_ar, $entity['Table']);
		
		foreach ($field_id_ar as $val)
		{
			$field = ZN_Field::select_line_by_id($val);
			$field_identified[] = $field['Identified'];
		}
		$sql_field = "\"".implode("\",\"", $field_identified)."\"";
		
		$sql = 
<<<SQL
\nALTER TABLE "{$entity['Table']}"
	ADD CONSTRAINT "{$constraint_name}" UNIQUE ({$sql_field});
SQL;
	
		return $sql;
	}
	
	/**
	 * Переименовать уникальный ключ
	 * 
	 * @param int $id
	 * @param string $name
	 * @return string 
	 */
	public static function unique_rename($id, $name)
	{
		$entity = ZN_Entity::select_line_by_id(ZN_Unique::select_entity_id_by_id($id));
		$field_id_ar = ZN_Unique::select_column_field_by_unique($id);
		$constraint_name = self::unique_get_name($field_id_ar, $entity['Table']);
		
		/* Удалить */
		$sql = 
<<<SQL
\nALTER TABLE "{$entity['Table']}"
	DROP CONSTRAINT "{$constraint_name}";
SQL;
		
		/* Добавить */
		foreach ($field_id_ar as $val)
		{
			$field = ZN_Field::select_line_by_id($val);
			$field_identified[] = $field['Identified'];
		}
		$sql_field = "\"".implode("\",\"", $field_identified)."\"";
		
		$sql .= 
<<<SQL
\nALTER TABLE "{$entity['Table']}"
	ADD CONSTRAINT "{$name}" UNIQUE ({$sql_field});
SQL;
		
		return $sql;
	}
	
	/**
	 * Удалить уникальный ключ
	 * 
	 * @param int $id
	 * @return string 
	 */
	public static function unique_delete($id)
	{
		$entity = ZN_Entity::select_line_by_id(ZN_Unique::select_entity_id_by_id($id));
		$field_id_ar = ZN_Unique::select_column_field_by_unique($id);
		
		$constraint_name = self::unique_get_name($field_id_ar, $entity['Table']);
		
		$sql = 
<<<SQL
\nALTER TABLE "{$entity['Table']}"
	DROP CONSTRAINT "{$constraint_name}";
SQL;
	
		return $sql;
	}
	
	/**
	 * Получить имя для уникального ключа
	 * 
	 * @param array $field_id_ar
	 * @param string $table
	 * @return string 
	 */
	public static function unique_get_name($field_id_ar, $table, $field_id=null, $field_identified=null)
	{
		$constraint_name = $table."_UN";
		foreach ($field_id_ar as $val)
		{
			if(!is_null($field_id) and $val == $field_id)
			{
				$constraint_name .= "_".$field_identified;
			}
			else
			{
				$field = ZN_Field::select_line_by_id($val);
				$constraint_name .= "_".$field['Identified'];
			}
		}
		
		return $constraint_name;
	}
}
?>
