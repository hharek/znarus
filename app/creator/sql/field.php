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
	 * @return boolean 
	 */
	public static function add($identified, $type, $null, $default, $foreign_id, $entity_id)
	{
		$entity = ZN_Entity::select_line_by_id($entity_id);
		$pack = ZN_Pack::select_line_by_id($entity['Pack_ID']);
		
		/* Наименование таблицы */
		$table = ZN_SQL_Entity::get_name_table($entity['Identified'], $pack['Identified']);
		
		/***-----------------SQL------------------***/
		switch ($type)
		{
			/*** ID ***/
			case "id":
			{
				$sql = 
<<<SQL
CREATE SEQUENCE "{$table}_seq" START 1;
ALTER TABLE "{$table}" 
	ADD "{$identified}" int NOT NULL DEFAULT nextval('{$table}_seq'),
	ADD CONSTRAINT "{$table}_PK" PRIMARY KEY ("{$identified}");
ALTER SEQUENCE "{$table}_seq" OWNED BY "{$table}"."{$identified}";
SQL;
			}
			break;
		
			/*** Sort ***/
			case "sort":
			{
				$query = 
<<<SQL
SELECT "f"."Identified"
FROM "field" as "f", "field_type" as "t"
WHERE "f"."Entity_ID" = $1
AND "f"."Type_ID" = "t"."ID"
AND "t"."Identified" = 'id'
SQL;
				$id_identified = Reg::db_creator()->query_one($query, $entity_id, array("field","field_type"));
				
				$sql = 
<<<SQL
ALTER TABLE "{$table}" 
	ADD "{$identified}" int;
UPDATE "{$table}" SET "{$identified}" = "{$id_identified}";
ALTER TABLE "{$table}"
	ALTER COLUMN "{$identified}" SET DEFAULT currval('{$table}_seq');
SQL;
			}
			break;
		
			/*** Enum ***/
			case "enum":
			{
				$sql_type_name = $pack['Identified']."_".$entity['Identified']."_".strtolower($identified);
				$sql =
<<<SQL
CREATE TYPE "{$sql_type_name}" AS ENUM ();
ALTER TABLE "{$table}"
	ADD "{$identified}" "{$sql_type_name}";
SQL;
			}
			break;
		
			/*** Foreign ***/
			case "foreign":
			{
				$ref_field = ZN_Field::select_line_by_id($foreign_id);
				$ref_field_type = ZN_Field_Type::get_type($ref_field['Type_ID']);
				if($ref_field_type == "id")
				{$ref_field_type = "int";}
				
				$ref_entity = ZN_Entity::select_line_by_id($ref_field['Entity_ID']);
				$ref_table = ZN_SQL_Entity::get_name_table($ref_entity['Identified'], $pack['Identified']);
				
				$sql = 
<<<SQL
ALTER TABLE "{$table}"
	ADD "{$identified}" {$ref_field_type} NULL,
	ADD CONSTRAINT "{$table}_FK_{$identified}" FOREIGN KEY ("{$identified}") REFERENCES "{$ref_table}"("{$ref_field['Identified']}");
SQL;
			}
			break;
		
			/*** Остальные ***/
			default :
			{
				$sql_type = self::get_sql($type, $null);
				$sql = 
<<<SQL
ALTER TABLE "{$table}"
	ADD "{$identified}" {$sql_type};
SQL;
	
				/* Default */
				if(mb_strlen($default, "UTF-8") > 0)
				{
					$sql .= 
<<<SQL
ALTER TABLE "{$table}" 
	ALTER COLUMN "{$identified}" SET DEFAULT '{$default}';
SQL;
				}
			}
			break;
		}
		
//		header("Content-Type: text/plain");
//		echo $sql;
//		exit();
		
		/*** Запрос ***/
		Reg::db()->multi_query($sql);
		
		return true;
	}
	
	/**
	 * Редактирование
	 * 
	 * @param int $id
	 * @param string $identified
	 * @param string $type
	 * @param bool $null
	 * @param string $default
	 * @param int $foreign_id
	 * @return boolean 
	 */
	public static function edit($id, $identified, $type, $null, $default, $foreign_id)
	{
		$sql = "";
		$field = ZN_Field::select_line_by_id($id);
		$field['type'] = ZN_Field_Type::get_type($field['Type_ID']);
		$entity = ZN_Entity::select_line_by_id($field['Entity_ID']);
		$pack = ZN_Pack::select_line_by_id($entity['Pack_ID']);
		
		/* Наименование таблицы */
		$table = ZN_SQL_Entity::get_name_table($entity['Identified'], $pack['Identified']);
		
		/***---------------Идентификатор------------***/
		if($field['Identified'] != $identified)
		{
			$sql .= 
<<<SQL
ALTER TABLE "{$table}"
	RENAME COLUMN "{$field['Identified']}" TO "{$identified}";
SQL;
		}
		
		/***-------------------Тип------------------***/
		if($field['type'] != $type)
		{
			$sql_type = self::get_sql($type);
			$sql .= 
<<<SQL
ALTER TABLE "{$table}"
	ALTER COLUMN "{$identified}" TYPE {$sql_type};
SQL;
		}
		
		/***------------------NULL-----------------***/
		if($field['Null'] != $null)
		{
			if($null)
			{
				$sql .= 
<<<SQL
ALTER TABLE {$table} 
	ALTER COLUMN "{$identified}" DROP NOT NULL;
SQL;
			}
			else
			{
				
			}
		}
		
		return true;
	}
	
	public static function delete()
	{
		return true;
	}
	
	/**
	 * Создать SQL для поля
	 * 
	 * @param string $operation (add|edit)
	 * @param string $type
	 * @param boolean $null
	 * @param string $default
	 * @return string
	 */
	public static function get_sql($operation, $type, $null, $default)
	{
		switch ($type)
		{
			/* INT */
			case "int":
			{
				$sql = " int";
				if(!$null)
				{
					$sql .= " NOT NULL DEFAULT 0";
				};
			}
			break;

			/* UINT */
			case "uint":
			{
				$sql = " int";
				if(!$null)
				{
					$sql .= " NOT NULL DEFAULT 0";
				};
			}
			break;

			/* FLOAT */
			case "float":
			{
				$sql = " float";
				if(!$null)
				{
					$sql .= " NOT NULL DEFAULT 0.0";
				};
			}
			break;

			/* PRICE */
			case "price":
			{
				$sql = " numeric(15,2)";
				if(!$null)
				{
					$sql .= " NOT NULL DEFAULT 0.00";
				};
			}
			break;

			/* STRING */
			case "string":
			{
				$sql = " varchar(255)";
				if(!$null)
				{
					$sql .= " NOT NULL DEFAULT ''";
				};
			}
			break;

			/* TEXT */
			case "text":
			{
				$sql = " text";
				if(!$null)
				{
					$sql .= " NOT NULL DEFAULT ''";
				};
			}
			break;

			/* HTML */
			case "html":
			{
				$sql = " text";
				if(!$null)
				{
					$sql .= " NOT NULL DEFAULT ''";
				};
			}
			break;

			/* IDENTIFIED */
			case "identified":
			{
				$sql = " varchar(127)";
				if(!$null)
				{
					$sql .= " NOT NULL DEFAULT ''";
				};
			}
			break;

			/* FILE */
			case "file":
			{
				$sql = " varchar(127)";
				if(!$null)
				{
					$sql .= " NOT NULL DEFAULT ''";
				};
			}
			break;

			/* IMAGE */
			case "image":
			{
				$sql = " varchar(127)";
				if(!$null)
				{
					$sql .= " NOT NULL DEFAULT ''";
				};
			}
			break;

			/* URL */
			case "url":
			{
				$sql = " varchar(255)";
				if(!$null)
				{
					$sql .= " NOT NULL DEFAULT ''";
				};
			}
			break;

			/* EMAIL */
			case "email":
			{
				$sql = " varchar(127)";
				if(!$null)
				{
					$sql .= " NOT NULL DEFAULT ''";
				};
			}
			break;

			/* DATE */
			case "date":
			{
				$sql = " date";
				if(!$null)
				{
					$sql .= " NOT NULL DEFAULT current_timestamp";
				};
			}
			break;

			/* TIMESTAMP */
			case "timestamp":
			{
				$sql = " timestamp";
				if(!$null)
				{
					$sql .= " NOT NULL DEFAULT current_timestamp";
				};
			}
			break;

			/* BOOL */
			case "bool":
			{
				$sql = " boolean";
				if(!$null)
				{
					$sql .= " NOT NULL DEFAULT false";
				};
			}
			break;

			/* MD5 */
			case "md5":
			{
				$sql = " char(32)";
				if(!$null)
				{
					$sql .= " NOT NULL DEFAULT ''";
				};
			}
			break;

			/* BLOB */
			case "blob":
			{
				$sql = " bytea";
				if(!$null)
				{
					$sql .= " NOT NULL DEFAULT ''";
				};
			}
			break;
		}

		return $sql;
	}
}
?>
