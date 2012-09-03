<?php
/**
 * SQL для полей
 */
class ZN_SQL_Field
{
	/**
	 * Добавить
	 * 
	 * @param string $identified
	 * @param string $type
	 * @param bool $null
	 * @param string $default
	 * @param int $foreign_id
	 * @param int $entity_id
	 * @return string 
	 */
	public static function add($identified, $type, $null, $default, $foreign_id, $entity_id)
	{
		$entity = ZN_Entity::select_line_by_id($entity_id);
		
		switch ($type)
		{
			/***--------------------------ID---------------------------***/
			case "id":
			{
				$sql = 
<<<SQL
CREATE SEQUENCE "{$entity['Table']}_seq" START 1;
ALTER TABLE "{$entity['Table']}" 
	ADD "{$identified}" int NOT NULL DEFAULT nextval('{$entity['Table']}_seq');
ALTER SEQUENCE "{$entity['Table']}_seq" OWNED BY "{$entity['Table']}"."{$identified}";
SQL;

				$sql .= ZN_SQL_Constraint::primary_add($identified, $entity_id);
			}
			break;
		
			/***-------------------------Sort---------------------------***/
			case "sort":
			{	
				$field_id = ZN_Entity::get_field_id($entity_id);
				
				$sql = 
<<<SQL
ALTER TABLE "{$entity['Table']}" 
	ADD "{$identified}" int;
UPDATE "{$entity['Table']}" SET "{$identified}" = "{$field_id['Identified']}";
ALTER TABLE "{$entity['Table']}"
	ALTER COLUMN "{$identified}" SET DEFAULT currval('{$entity['Table']}_seq');
SQL;
			}
			break;
		
			/***---------------------------Enum-------------------------***/
			case "enum":
			{
				$sql_type_name = ZN_SQL_Enum::get_name($entity['Table'], $identified);
				$sql =
<<<SQL
CREATE TYPE "{$sql_type_name}" AS ENUM ();
ALTER TABLE "{$entity['Table']}"
	ADD "{$identified}" "{$sql_type_name}";
SQL;
			}
			break;
		
			/***--------------------------Foreign-------------------------***/
			case "foreign":
			{
				$sql = ZN_SQL_Constraint::foreign_add($identified, $foreign_id, $entity_id);
			}
			break;
		
			/***---------------------Простые типы----------------------***/
			default :
			{
				$sql = 
<<<SQL
ALTER TABLE "{$entity['Table']}"
	ADD "{$identified}"
SQL;
				$sql_type = self::get_sql($type);
	
				$sql .= " ".$sql_type['type'];
				if(!$null)
				{
					$sql .= " NOT NULL";
					if(mb_strlen($default, "UTF-8") > 0)
					{$sql .= " DEFAULT '{$default}'";}
					else 
					{$sql .= " DEFAULT ".$sql_type['default'];}
				};
				$sql .= ";";
	
				/*** Default ***/
				if(mb_strlen($default, "UTF-8") > 0)
				{
					$sql .= 
<<<SQL
ALTER TABLE "{$entity['Table']}" 
	ALTER COLUMN "{$identified}" SET DEFAULT '{$default}';
SQL;
				}
				
			}
			break;
		}
		
		return $sql;
	}
	
	/**
	 * Редактирование
	 * 
	 * @param int $id
	 * @param string $identified
	 * @param string $type
	 * @param bool $null
	 * @param string $default
	 * @return string 
	 */
	public static function edit($id, $identified, $type, $null, $default)
	{
		$sql = "";
		$field = ZN_Field::select_line_by_id($id);
		$field['type'] = ZN_Field_Type::get_type($field['Type_ID']);
		$entity = ZN_Entity::select_line_by_id($field['Entity_ID']);
		
		/*-----------Идентификатор--------*/
		if($field['Identified'] != $identified)
		{
			/* Переименовать тип для перечисления */
			if($field['type'] == "enum")
			{
				$sql .= ZN_SQL_Enum::rename($id, ZN_SQL_Enum::get_name($entity['Table'], $identified));
			}
			
			/* Foreign */
			if($field['type'] == "foreign")
			{
				$sql .= ZN_SQL_Constraint::foreign_rename($id, ZN_SQL_Constraint::foreign_get_name($entity['Table'], $identified));
			}
			
			/* Уникальные ключи */
			$unique = ZN_Unique::select_column_by_field_id($id);
			foreach ($unique as $val)
			{			
				$field_id_ar = ZN_Unique::select_column_field_by_unique($val);
				$sql .= ZN_SQL_Constraint::unique_rename($val, ZN_SQL_Constraint::unique_get_name($field_id_ar, $entity['Table'], $id, $identified));
			}
			
			/* Переименовать колонку */
			$sql .= 
<<<SQL
ALTER TABLE "{$entity['Table']}"
	RENAME COLUMN "{$field['Identified']}" TO "{$identified}";
SQL;
		}
		
		/*--------------Тип--------------*/
		$sql_type = self::get_sql($type);
		if($field['type'] != $type)
		{
			$sql .= 
<<<SQL
ALTER TABLE "{$entity['Table']}"
	ALTER COLUMN "{$identified}" TYPE {$sql_type['type']}
		USING "{$identified}"::{$sql_type['type']};
SQL;
		}
	
		/*-------------NULL-------------*/
		if($field['Null'] != $null)
		{
			if($null)
			{
				$sql .= 
<<<SQL
ALTER TABLE "{$entity['Table']}" 
	ALTER COLUMN "{$identified}" DROP NOT NULL;
SQL;
			}
			else
			{
				if(mb_strlen($default, "UTF-8") > 0)
				{$null_new = $default;}
				else 
				{$null_new = $sql_type['default'];}
				
				$sql .= 
<<<SQL
UPDATE "{$entity['Table']}"
SET "{$identified}" = {$null_new}
WHERE "{$identified}" IS NULL;
						
ALTER TABLE "{$entity['Table']}" 
	ALTER COLUMN "{$identified}" SET NOT NULL;
SQL;
			}
		}
		
		/*-------------Default-------------*/
		if($field['Default'] != $default)
		{
			if(mb_strlen($default, "UTF-8") > 0)
			{
				$sql .= 
<<<SQL
ALTER TABLE "{$entity['Table']}" 
	ALTER COLUMN "{$identified}" SET DEFAULT '{$default}';
SQL;
			}
			else
			{
				$sql .= 
<<<SQL
ALTER TABLE "{$entity['Table']}" 
	ALTER COLUMN "{$identified}" DROP DEFAULT;
SQL;
			}
		}
		
		return $sql;
	}
	
	/**
	 * Удаление
	 * 
	 * @param int $id
	 * @return string 
	 */
	public static function delete($id)
	{
		$sql = "";
		$field = ZN_Field::select_line_by_id($id);
		$field['type'] = ZN_Field_Type::get_type($field['Type_ID']);
		$entity = ZN_Entity::select_line_by_id($field['Entity_ID']);
		
		$sql .= 
<<<SQL
ALTER TABLE "{$entity['Table']}"
	DROP COLUMN "{$field['Identified']}";
SQL;
		return $sql;
	}
	
	/**
	 * Получить SQL-ные записи по типу
	 * 
	 * @param string $type
	 * @return array 
	 */
	public static function get_sql($type)
	{
		switch ($type)
		{
			/* BLOB */
			case "blob":
			{
				$sql_type = "bytea";
				$sql_default = "''";
			}
			break;
		
			/* BOOL */
			case "bool":
			{
				$sql_type = "boolean";
				$sql_default = "false";
			}
			break;
		
			/* DATE */
			case "date":
			{
				$sql_type = "date";
				$sql_default = "current_timestamp";
			}
			break;
		
			/* EMAIL */
			case "email":
			{
				$sql_type = "varchar(127)";
				$sql_default = "''";
			}
			break;
		
			/* FILE */
			case "file":
			{
				$sql_type = "varchar(127)";
				$sql_default = "''";
			}
			break;
		
			/* FLOAT */
			case "float":
			{
				$sql_type = "float";
				$sql_default = "0.0";
			}
			break;
		
			/* HTML */
			case "html":
			{
				$sql_type = "text";
				$sql_default = "''";
			}
			break;
		
			/* IDENTIFIED */
			case "identified":
			{
				$sql_type = "varchar(127)";
				$sql_default = "''";
			}
			break;
		
			/* IMAGE */
			case "image":
			{
				$sql_type = "varchar(127)";
				$sql_default = "''";
			}
			break;
		
			/* INT */
			case "int":
			{
				$sql_type = "int";
				$sql_default = "0";
			}
			break;

			/* MD5 */
			case "md5":
			{
				$sql_type = "char(32)";
				$sql_default = "''";
			}
			break;
		
			/* PRICE */
			case "price":
			{
				$sql_type = "numeric(15,2)";
				$sql_default = "0.00";
			}
			break;
		
			/* STRING */
			case "string":
			{
				$sql_type = "varchar(255)";
				$sql_default = "''";
			}
			break;
		
			/* TEXT */
			case "text":
			{
				$sql_type = "text";
				$sql_default = "''";
			}
			break;
		
			/* TIMESTAMP */
			case "timestamp":
			{
				$sql_type = "timestamp";
				$sql_default = "current_timestamp";
			}
			break;
		
			/* UINT */
			case "uint":
			{
				$sql_type = "int";
				$sql_default = "0";
			}
			break;

			/* URL */
			case "url":
			{
				$sql_type = "varchar(255)";
				$sql_default = "''";
			}
			break;
		
			default :
			{
				$sql_type = "";
				$sql_default = "";
			}
			break;
		}
		
		return array("type"=>$sql_type, "default"=>$sql_default);
	}
}
?>