<?php
/**
 * SQL для сущностей 
 */
class ZN_SQL_Enum
{
	/**
	 * Добавить
	 * 
	 * @param string $name
	 * @param int $field_id
	 * @return string
	 */
	public static function add($name, $field_id)
	{
		$field = ZN_Field::select_line_by_id($field_id);
		
		/* Список перечислений */
		$enum_ar = ZN_Enum::select_column_by_field_id($field_id);
		$enum_ar[] = $name;
		
		$sql = self::create($field_id, $enum_ar);
		
		return $sql;
	}
	
	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $name
	 * @return boolean 
	 */
	public static function edit($id, $name)
	{
		$enum = ZN_Enum::select_line_by_id($id);
		
		/* Список перечислений */
		$enum_ar = ZN_Enum::select_column_by_field_id($enum['Field_ID']);
		foreach ($enum_ar as $key=>$val)
		{
			if($val == $enum['Name'])
			{ 
				$enum_ar[$key] = $name;
				break;
			}
		}
		
		$sql = self::create($enum['Field_ID'], $enum_ar);
		
		return $sql;
	}
	
	/**
	 * Удалить
	 * 
	 * @param int $id
	 * @return boolean 
	 */
	public static function delete($id)
	{
		$enum = ZN_Enum::select_line_by_id($id);
		
		/* Список перечислений */
		$enum_ar = ZN_Enum::select_column_by_field_id($enum['Field_ID']);
		foreach ($enum_ar as $key=>$val)
		{
			if($val == $enum['Name'])
			{ 
				unset($enum_ar[$key]);
				break;
			}
		}
		
		$sql = self::create($enum['Field_ID'], $enum_ar);
		
		return $sql;
	}
	
	/**
	 * Удалить старый и создать новый тип
	 * 
	 * @param int $field_id
	 * @param array $enum
	 * @return string 
	 */
	public static function create($field_id, $enum)
	{
		$field = ZN_Field::select_line_by_id($field_id);
		$entity = ZN_Entity::select_line_by_id($field['Entity_ID']);
		
		$enum_str = "'".implode("','", $enum)."'";
		$type_name = self::get_name($entity['Table'], $field['Identified']);
		
		/* Отсоединяем от поля */
		$sql = 
<<<SQL
\nALTER TABLE "{$entity['Table']}"
	ALTER COLUMN "{$field['Identified']}" TYPE varchar(127);
SQL;

		/* Default приводим к типу varchar */
		if(mb_strlen($field['Default'], "UTF-8") > 0)
		{
			$sql .= 
<<<SQL
\nALTER TABLE "{$entity['Table']}"
	ALTER COLUMN "{$field['Identified']}" SET DEFAULT '{$field['Default']}'::varchar(127);
SQL;
		}
		
		/* Создаём и удаляем тип */
		$sql .= 
<<<SQL
\nDROP TYPE "{$type_name}";
CREATE TYPE "{$type_name}" AS ENUM ({$enum_str});
SQL;

		/* Default приводим к созданному типу */
		if(mb_strlen($field['Default'], "UTF-8") > 0)
		{
			$sql .= 
<<<SQL
\nALTER TABLE "{$entity['Table']}"
	ALTER COLUMN "{$field['Identified']}" SET DEFAULT '{$field['Default']}'::"{$type_name}";
SQL;
		}

		/* Привязываем поле к новому типу */
		$sql .= 
<<<SQL
\nALTER TABLE "{$entity['Table']}"
	ALTER COLUMN "{$field['Identified']}" TYPE "{$type_name}"
		USING "{$field['Identified']}"::"{$type_name}";
SQL;
		
		return $sql;
	}
	
	/**
	 * Переименовать тип
	 * 
	 * @param type $field_id
	 * @param type $name
	 * @return string 
	 */
	public static function rename($field_id, $name)
	{
		$field = ZN_Field::select_line_by_id($field_id);
		$entity = ZN_Entity::select_line_by_id($field['Entity_ID']);
		
		$type_name = self::get_name($entity['Table'], $field['Identified']);
		$sql =
<<<SQL
\nALTER TYPE "{$type_name}" RENAME TO "{$name}";
SQL;
		
		return $sql;
	}
	
	/**
	 * Удалить sql-тип привязанный к полю
	 * 
	 * @param string $name
	 * @return string 
	 */
	public static function drop_type($field_id)
	{
		$field = ZN_Field::select_line_by_id($field_id);
		$entity = ZN_Entity::select_line_by_id($field['Entity_ID']);
		$type_name = self::get_name($entity['Table'], $field['Identified']);
		
		$sql = "";
		
		/* Отсоединяем от поля */
		$sql = 
<<<SQL
\nALTER TABLE "{$entity['Table']}"
	ALTER COLUMN "{$field['Identified']}" TYPE varchar(127);

ALTER TABLE "{$entity['Table']}"
	ALTER COLUMN "{$field['Identified']}" DROP DEFAULT;
SQL;
		
		/* Удаляем тип */
		$sql .=
<<<SQL
\nDROP TYPE "{$type_name}";
SQL;
		
		return $sql;
	}
	
	/**
	 * Получить имя для идентификатора
	 * 
	 * @param string $table
	 * @param string $identified
	 * @return string 
	 */
	public static function get_name($table, $identified)
	{
		return $table."_".strtolower($identified);;
	}
}
?>
