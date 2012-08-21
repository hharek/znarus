<?php
class Creater
{
	/**
	 * Создать SQL для сущности
	 * 
	 * @param int $id
	 * @return string
	 */
	public static function entity_sql($id)
	{
		/* Проверка */
		ZN_Entity::is_id($id);
		
		/* Сущность */
		$entity = ZN_Entity::select_line_by_id($id);
		
		/* Пакет */
		$pack = ZN_Pack::select_line_by_id($entity['Pack_ID']);
		
		/* Поля */
		$field = ZN_Field::select_list_by_entity_id($id);
		
		/* Определение ID, перечислений, внешних ключей */
		$is_id = false;
		$enum = array();
		$foreign = array();
		foreach ($field as $val)
		{
			if($val['type_identified'] == "id")
			{
				$is_id = true;
				$id_identified = $val['Identified'];
			}
			
			if($val['type_identified'] == "enum")
			{
				$enum[] = $val;
			}
			
			if($val['type_identified'] == "foreign")
			{
				$foreign[] = $val;
			}
		}
		
		/*---------------- SQL общий. Начало ---------------*/
		$sql = "";
		
		/* Создание типов для перечисления */
		if(!empty($enum))
		{
			foreach ($enum as $val)
			{
				$query = 
<<<SQL
SELECT "Name"
FROM "enum"
WHERE "Field_ID" = $1
ORDER BY "Sort" ASC
SQL;
				$enum_array = Reg::db_creator()->query_column($query, $val['ID'], "enum");
				$sql .= "\nCREATE TYPE \"{$pack['Identified']}_{$entity['Identified']}_".strtolower($val['Identified'])."\" AS ENUM ('".implode("','", $enum_array)."');";
			}
		}
		
		/* Счётчик */
		if($is_id)
		{
			$sql .= "\nCREATE SEQUENCE \"{$pack['Identified']}_{$entity['Identified']}_seq\";";
		}
		
		/* Наименование табицы */
		$sql .= "\nCREATE TABLE \"{$pack['Identified']}_{$entity['Identified']}\"";
		$sql .= "\n(";
		
		
		
		/*------------------ SQL полей ---------------------*/
		foreach ($field as $key=>$val)
		{
			if($val['type_identified'] == "foreign")
			{continue;}
			
			$sql .= "\n\t";
			$sql .= self::field_sql($pack['Identified']."_".$entity['Identified'], $val['Identified'], $val['type_identified'], $val['Null'], $val['Default']);

			if($key != count($field)-1)
			{
				$sql .= ",";
			}
		}
		
		/*----------------- SQL ключей ---------------------*/
		/* Первичный ключ */
		if($is_id)
		{
			$sql .= ",\n\tCONSTRAINT \"{$pack['Identified']}_{$entity['Identified']}_PK\" PRIMARY KEY (\"{$id_identified}\")";
		}
		
		/* Уникальные ключи */
		
		
		/*----------------- SQL общий. Конец ---------------*/
		$sql .= "\n);";


		/* Привязка счётчика */
		if($is_id)
		{
			$sql .= "\nALTER SEQUENCE \"{$pack['Identified']}_{$entity['Identified']}_seq\" OWNED BY \"{$pack['Identified']}_{$entity['Identified']}\".\"{$id_identified}\";";
		}
		
		/*------------------- Внешние ключи ----------------*/
		if(!empty($foreign))
		{
			foreach ($foreign as $val)
			{
				$field_current = ZN_Field::select_line_by_id($val['ID']);
				
				$field_references = ZN_Field::select_line_by_id($field_current['Foreign_ID']);
				$field_references_type = ZN_Field_Type::get_type($field_references['Type_ID']);
				if($field_references_type == "id")
				{$field_references_type = "int";}
				
				$entity_references = ZN_Entity::select_line_by_id($field_references['Entity_ID']);
				
				$sql .= 
<<<SQL
\nALTER TABLE "{$pack['Identified']}_{$entity['Identified']}"
	ADD "{$field_current['Identified']}" {$field_references_type} NOT NULL,
	ADD CONSTRAINT "{$pack['Identified']}_{$entity['Identified']}_FK_{$field_current['Identified']}" FOREIGN KEY ("{$field_current['Identified']}") REFERENCES "{$pack['Identified']}_{$entity_references['Identified']}"("{$field_references['Identified']}");
SQL;
			}
		}
		
		return $sql;
	}
	
	/**
	 * Создать SQL для поля
	 * 
	 * @param string $table
	 * @param string $name
	 * @param string $type
	 * @param string $null
	 * @param string $default
	 * @return string
	 */
	public static function field_sql($table, $name, $type, $null, $default)
	{
		$sql = "\"{$name}\"";
		$null = (boolean)$null;
		
		/***------------------- Простые поля -----------------***/
		if(!in_array($type, array("id","sort","enum","foreign")))
		{
			switch ($type)
			{
				/* INT */
				case "int":
				{
					$sql .= " int";
				}
				break;

				/* UINT */
				case "uint":
				{
					$sql .= " int";
				}
				break;

				/* FLOAT */
				case "float":
				{
					$sql .= " float";
				}
				break;

				/* PRICE */
				case "price":
				{
					$sql .= " numeric(15,2)";
				}
				break;

				/* STRING */
				case "string":
				{
					$sql .= " varchar(255)";
				}
				break;

				/* TEXT */
				case "text":
				{
					$sql .= " text";
				}
				break;

				/* HTML */
				case "html":
				{
					$sql .= " text";
				}
				break;

				/* IDENTIFIED */
				case "identified":
				{
					$sql .= " varchar(127)";
				}
				break;

				/* FILE */
				case "file":
				{
					$sql .= " varchar(127)";
				}
				break;

				/* IMAGE */
				case "image":
				{
					$sql .= " varchar(127)";
				}
				break;

				/* URL */
				case "url":
				{
					$sql .= " varchar(255)";
				}
				break;

				/* EMAIL */
				case "email":
				{
					$sql .= " varchar(127)";
				}
				break;

				/* DATE */
				case "date":
				{
					$sql .= " date";
				}
				break;

				/* TIMESTAMP */
				case "timestamp":
				{
					$sql .= " timestamp";
				}
				break;

				/* BOOL */
				case "bool":
				{
					$sql .= " boolean";
				}
				break;

				/* MD5 */
				case "md5":
				{
					$sql .= " char(32)";
				}
				break;

				/* BLOB */
				case "blob":
				{
					$sql .= " bytea";
				}
				break;
			}
			
			/*** NULL ***/
			if($null)
			{
				$sql .= " NULL";
			}
			else
			{
				$sql .= " NOT NULL";
			}
			
			/*** DEFAULT ***/
			if(!empty($default))
			{
				$sql .= "DEFAULT '{$default}'";
			}
		}
		/***------------------- Сложные поля -----------------***/
		else
		{
			switch ($type)
			{
				/* ID */
				case "id":
				{
					$sql .= " int NOT NULL DEFAULT nextval('{$table}_seq')";
				}
				break;
			
				/* SORT */
				case "sort":
				{
					$sql .= " int NOT NULL DEFAULT currval('{$table}_seq')";
				}
				break;
			
				/* ENUM */
				case "enum":
				{
					$sql .= " \"".$table."_".strtolower($name)."\" DEFAULT '{$default}'";
				}
				break;
			
				/* FOREIGN */
				case "foreign":
				{
					$sql .= " ";
				}
				break;
			}
		}
		
		return $sql;
	}
}
?>
