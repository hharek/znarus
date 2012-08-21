<?php
/**
 * Поля
 */
class ZN_Field
{
	/**
	 * Добавить
	 * 
	 * @param string $name
	 * @param string $identified
	 * @param int $type_id
	 * @param string $desc
	 * @param int $null
	 * @param string $default
	 * @param int $foreign_id
	 * @param int $entity_id
	 * @return bool
	 */
	public static function add($name, $identified, $type_id, $desc, $null, $default, $foreign_id, $entity_id)
	{
		/* Проверка */
		if(!Chf::string($name))
		{Err::add("Наименование задано неверно. ".Chf::error(), "name");}
		
		if(!Chf::identified($identified))
		{Err::add("Идентификатор задано неверно. ".Chf::error(), "identified");}
		$identified = ucfirst($identified);
		
		ZN_Field_Type::is_id($type_id);
		$type = ZN_Field_Type::get_type($type_id);
		
		if(!empty ($desc) and !Chf::text($desc))
        {Err::add("Описание задано неверно. ".Chf::error(), "desc");}
		
		ZN_Entity::is_id($entity_id);
		
		/* NULL */
		if(in_array($type, array("id", "sort")))
		{$null = false;}
		
		$null = (bool)$null;
		
		/* DEFAULT */
		if(in_array($type, array("id","sort","enum")))
		{$default = "";}
		
		$default = trim($default);
		
		if(mb_strlen($default, "UTF-8") > 0)
        {
			if(!Chf::check($default))
			{
				Err::add("Значение по умолчанию задано неверно. ".Chf::error(), "default");
			}
			
			self::check_default($type, $default);
		}
		
		/* FOREIGN */
		if($type == "foreign")
		{
			self::is_id($foreign_id);
		}
		else
		{
			$foreign_id = null;
		}
		
		/* Sort */
		if($type == "sort")
		{
			if(!self::check_add_sort($entity_id))
			{Err::add("Невозможно добавить поле типа \"sort\", если у сущности нет поля типа \"id\". ", "type_id");}
		}
		
		/* ID */
		if($type == "id")
		{
			if(!self::check_add_id($entity_id))
			{Err::add("Поле типа \"id\" уже существует. ", "type_id");}
		}
		
		Err::exception();
		
		/* Уникальность */
		self::_unique($name, $identified, $entity_id);
		
		Err::exception();
		
		/* Выполнить SQL */
		ZN_SQL_Field::add($identified, $type, $null, $default, $foreign_id, $entity_id);
		
		/* Добавить */
		$query = 
<<<SQL
INSERT INTO "field"("Name", "Identified", "Type_ID", "Desc", "Null", "Default", "Foreign_ID", "Entity_ID")
VALUES ($1, $2, $3, $4, $5, $6, $7, $8)
SQL;
		Reg::db_creator()->query($query, array($name, $identified, $type_id, $desc, $null, $default, $foreign_id, $entity_id), "field", true);
		
		return true;
	}
	
	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $name
	 * @param string $identified
	 * @param int $type_id
	 * @param string $desc
	 * @param int $null
	 * @param string $default
	 * @param int $foreign_id
	 * @return bool
	 */
	public static function edit($id, $name, $identified, $type_id, $desc, $null, $default, $foreign_id)
	{
		/* Проверка */
		self::is_id($id);
		$field = self::select_line_by_id($id);
		$field['type'] = ZN_Field_Type::get_type($field['Type_ID']);
		
		if(!Chf::string($name))
		{Err::add("Наименование задано неверно. ".Chf::error(), "name");}
		
		if(!Chf::identified($identified))
		{Err::add("Идентификатор задано неверно. ".Chf::error(), "identified");}
		$identified = ucfirst($identified);
		
		if(!empty ($desc) and !Chf::text($desc))
        {Err::add("Описание задано неверно. ".Chf::error(), "desc");}
		
		/* Типы */
		ZN_Field_Type::is_id($type_id);
		$type = ZN_Field_Type::get_type($type_id);
		
		if($field['type'] != $type)
		{
			if(in_array($field['type'], array("id","sort","enum","foreign")) or in_array($type, array("id","sort","enum","foreign")))
			{
				Err::add("Невозможно преобразовывать типы \"id\", \"sort\", \"enum\", \"foreign\" ", "type_id");
			}
		}
		
		/* NULL */
		if(in_array($type, array("id","sort")))
		{$null = false;}
		
		$null = (bool)$null;
		
		/* DEFAULT */
		if(in_array($type, array("id","sort")))
		{$default = "";}
		
		$default = trim($default);
		
		if(mb_strlen($default, "UTF-8") > 0)
        {
			if(!Chf::check($default))
			{
				Err::add("Значение по умолчанию задано неверно. ".Chf::error(), "default");
			}
			
			self::check_default($type, $default);
		}
		
		/* FOREIGN */
		if($type == "foreign")
		{
			self::is_id($foreign_id);
		}
		else
		{
			$foreign_id = null;
		}
		
		/* Sort */
		if($type == "sort")
		{
			if(!self::check_add_sort($entity_id))
			{Err::add("Невозможно добавить поле типа \"sort\", если у сущности нет поля типа \"id\". ", "type_id");}
		}
		
		/* ID */
		if($field['type'] != "id" and $type == "id")
		{
			if(!self::check_add_id($entity_id))
			{Err::add("Поле типа \"id\" уже существует. ", "type_id");}
		}
		
		Err::exception();
		
		/* Уникальность */
		$query = 
<<<SQL
SELECT "Entity_ID"
FROM "field"
WHERE "ID" = $1
SQL;
        $entity_id = Reg::db_creator()->query_one($query, $id, "field");
		self::_unique($name, $identified, $entity_id, $id);
		
		Err::exception();
		
		/* Удаление перечислений при изменении типа */
		if($field['type'] == "enum" and $type != "enum")
		{
			$query = 
<<<SQL
SELECT "ID"
FROM "enum"
WHERE "Field_ID" = $1
SQL;
			$enum = Reg::db_creator()->query_column($query, $id, "enum");
			if(!empty ($enum))
			{
				foreach($enum as $val)
				{
					ZN_Enum::delete($val);
				}
			}
		}
		
		/* Выполнить SQL */
		ZN_SQL_Field::edit($id, $identified, $type, $null, $default, $foreign_id);
		
		/* Редактировать */
		$query = 
<<<SQL
UPDATE "field"
SET 
	"Name" = $1, 
	"Identified" = $2, 
	"Type_ID" = $3, 
	"Desc" = $4, 
	"Null" = $5,
	"Default" = $6, 
	"Foreign_ID" = $7
WHERE "ID" = $8
SQL;
		Reg::db_creator()->query($query, array($name, $identified, $type_id, $desc, $null, $default, $foreign_id, $id), "field", true);
		
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
SELECT "ID"
FROM "enum"
WHERE "Field_ID" = $1
SQL;
		$enum = Reg::db_creator()->query_column($query, $id, "enum");
		if(!empty ($enum))
		{
			foreach($enum as $val)
			{
				ZN_Enum::delete($val);
			}
		}
		
		/* Удалить */
		$query = 
<<<SQL
DELETE
FROM "field"
WHERE "ID" = $1
SQL;
		Reg::db_creator()->query($query, $id, "field", true);
		
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
		{throw new Exception_Creator("Номер поля задан неверно. ".Chf::error());}
		
		$query = 
<<<SQL
SELECT COUNT(*) as count
FROM "field"
WHERE "ID" = $1
SQL;
		$count = Reg::db_creator()->query_one($query, $id, "field");
		if($count < 1)
		{throw new Exception_Creator("Поля с номером \"{$id}\" не существует.");}
		
		return true;
	}
	
	/**
	 * Сортировка верх
	 * 
	 * @param int $id
	 * @return bool
	 */
	public static function sort_up($id)
	{
		$field = self::select_line_by_id($id);
		
		$query = 
<<<SQL
SELECT "ID", "Sort"
FROM "field"
WHERE "Entity_ID" = $1
ORDER BY "Sort" ASC
SQL;
        $field_entity = Reg::db_creator()->query_assoc($query, $field['Entity_ID'], "field");
		if(count($field_entity) < 2)
		{return true;}
		
		foreach ($field_entity as $key=>$val)
		{
			if($val['ID'] == $id)
			{
				break;
			}
		}
		
		if($key == 0)
		{throw new Exception_Creator("Выше некуда.");}
		
		$id = $field_entity[$key]['ID'];
		$id_up = $field_entity[$key-1]['ID'];
		$sort = $field_entity[$key-1]['Sort'];
		$sort_up = $field_entity[$key]['Sort'];
		
		$query = 
<<<SQL
UPDATE "field"
SET 
	"Sort" = $1
WHERE "ID" = $2
SQL;
		Reg::db_creator()->query($query, array($sort, $id), "field", true);
		
		$query = 
<<<SQL
UPDATE "field"
SET 
	"Sort" = $1
WHERE "ID" = $2
SQL;
		Reg::db_creator()->query($query, array($sort_up, $id_up), "field", true);
		
		return true;
	}
	
	/**
	 * Сортировка вниз
	 * 
	 * @param int $id
	 * @return bool
	 */
	public static function sort_down($id)
	{
		$field = self::select_line_by_id($id);
		
		$query = 
<<<SQL
SELECT "ID", "Sort"
FROM "field"
WHERE "Entity_ID" = $1
ORDER BY "Sort" ASC
SQL;
        $field_entity = Reg::db_creator()->query_assoc($query, $field['Entity_ID'], "field");
		if(count($field_entity) < 2)
		{return true;}
		
		foreach ($field_entity as $key=>$val)
		{
			if($val['ID'] == $id)
			{
				break;
			}
		}
		
		if($key == count($field_entity)-1)
		{throw new Exception_Creator("Ниже некуда.");}
		
		$id = $field_entity[$key]['ID'];
		$id_up = $field_entity[$key+1]['ID'];
		$sort = $field_entity[$key+1]['Sort'];
		$sort_up = $field_entity[$key]['Sort'];
		
		$query = 
<<<SQL
UPDATE "field"
SET 
	"Sort" = $1
WHERE "ID" = $2
SQL;
		Reg::db_creator()->query($query, array($sort, $id), "field", true);
		
		$query = 
<<<SQL
UPDATE "field"
SET 
	"Sort" = $1
WHERE "ID" = $2
SQL;
		Reg::db_creator()->query($query, array($sort_up, $id_up), "field", true);
		
		return true;
	}

	/**
	 * Проверка поля DEFAULT
	 * 
	 * @param string $type
	 * @param string $default
	 * @return boolean 
	 */
	public static function check_default($type, $default)
	{
		switch ($type)
		{
			case "blob":
			{}
			break;
		
			case "bool":
			{
				if($default !== "0" and $default !== "1")
				{
					Err::add("Значение по умолчанию задано неверно. Необходимо указать \"0\" или \"1\" ", "default");
				}
			}
			break;
		
			case "date":
			{			
				if(!Chf::date($default))
				{
					Err::add("Значение по умолчанию задано неверно. " . Chf::error(), "default");
				}
			}
			break;
		
			case "email":
			{
				if (!Chf::email($default))
				{
					Err::add("Значение по умолчанию задано неверно. " . Chf::error(), "default");
				}
			}
			break;
		
			case "file":
			{
				if (!Chf::file($default))
				{
					Err::add("Значение по умолчанию задано неверно. " . Chf::error(), "default");
				}
			}
			break;
		
			case "float":
			{
				if (!Chf::float($default))
				{
					Err::add("Значение по умолчанию задано неверно. " . Chf::error(), "default");
				}
			}
			break;
		
			case "html":
			{
				if (!Chf::html($default))
				{
					Err::add("Значение по умолчанию задано неверно. " . Chf::error(), "default");
				}
			}
			break;
		
			case "identified":
			{
				if (!Chf::identified($default))
				{
					Err::add("Значение по умолчанию задано неверно. " . Chf::error(), "default");
				}
			}
			break;
		
			case "image":
			{
				if (!preg_match("#^[a-z0-9_\-.]+\.(gif|jpg|png)+$#isu", $default))
				{
					Err::add("Значение по умолчанию задано неверно. Допускаются символы a-z,0-9,\"_\", \".\" в наименовании и расширение gif, jpg, png.", "default");
				}
			}
			break;
		
			case "int":
			{
				if (!Chf::int($default))
				{
					Err::add("Значение по умолчанию задано неверно. " . Chf::error(), "default");
				}
			}
			break;
		
			case "md5":
			{
				if (!preg_match("#^[a-z0-9]+$#isu", $default))
				{
					Err::add("Значение по умолчанию задано неверно. Допускаются символы a-z,0-9.", "default");
					return false;
				}
				
				if(mb_strlen($default, "UTF-8") != 32)
				{
					Err::add("Значение по умолчанию задано неверно. Строка должна содержать 32 символа.", "default");
					return false;
				}
			}
			break;
		
			case "price":
			{
				if (!Chf::price($default))
				{
					Err::add("Значение по умолчанию задано неверно. " . Chf::error(), "default");
				}
			}
			break;
		
			case "string":
			{
				if (!Chf::string($default))
				{
					Err::add("Значение по умолчанию задано неверно. " . Chf::error(), "default");
				}
			}
			break;
		
			case "text":
			{
				if (!Chf::text($default))
				{
					Err::add("Значение по умолчанию задано неверно. " . Chf::error(), "default");
				}
			}
			break;
		
			case "timestamp":
			{
				if (!Chf::timestamp($default))
				{
					Err::add("Значение по умолчанию задано неверно. " . Chf::error(), "default");
				}
			}
			break;
		
			case "uint":
			{
				if (!Chf::uint($default))
				{
					Err::add("Значение по умолчанию задано неверно. " . Chf::error(), "default");
				}
			}
			break;
		
			case "url":
			{
				if (!Chf::url($default))
				{
					Err::add("Значение по умолчанию задано неверно. " . Chf::error(), "default");
				}
			}
			break;
		}
		
		return true;
	}

	/**
	 * Проверить возможность добавления Sort
	 * 
	 * @param int $entity_id
	 * @return boolean 
	 */
	public static function check_add_sort($entity_id)
	{
		$query = 
<<<SQL
SELECT COUNT("f".*) as count
FROM "field" as "f", "field_type" as "t"
WHERE "f"."Entity_ID" = $1
AND "f"."Type_ID" = "t"."ID"
AND "t"."Identified" = 'id'
SQL;
		$count = Reg::db_creator()->query_one($query, $entity_id, array("field","field_type"));
		if($count < 1)
		{return false;}
		else
		{return true;}
	}
	
	/**
	 * Проверить возможность добавления ID
	 * 
	 * @param int $entity_id
	 * @return boolean 
	 */
	public static function check_add_id($entity_id)
	{
		$query = 
<<<SQL
SELECT COUNT("f".*) as count
FROM "field" as "f", "field_type" as "t"
WHERE "f"."Entity_ID" = $1
AND "f"."Type_ID" = "t"."ID"
AND "t"."Identified" = 'id'
SQL;
		$count = Reg::db_creator()->query_one($query, $entity_id, array("field","field_type"));
		if($count > 0)
		{return false;}
		else
		{return true;}
	}
	
	/**
	 * Уникальность
	 * 
	 * @param string $name
	 * @param string $identified
	 * @param int $entity_id
	 * @param int $id
	 * @return bool
	 */
	private static function _unique($name, $identified, $entity_id, $id=null)
	{
		/* Наименование */
		$query = 
<<<SQL
SELECT COUNT(*) as count
FROM "field"
WHERE "Name" = $1
AND "Entity_ID" = $2
SQL;
		if(!is_null($id))
		{$query .= "AND \"ID\" != '{$id}'";}
		
		$count = Reg::db_creator()->query_one($query, array($name, $entity_id), "field");
		if($count > 0)
		{Err::add("Поле с именем \"{$name}\" уже существует.", "name");}
		
		/* Наименование */
		$query = 
<<<SQL
SELECT COUNT(*) as count
FROM "field"
WHERE "Identified" = $1
AND "Entity_ID" = $2
SQL;
		if(!is_null($id))
		{$query .= "AND \"ID\" != '{$id}'";}
		
		$count = Reg::db_creator()->query_one($query, array($identified, $entity_id), "field");
		if($count > 0)
		{Err::add("Поле с идентификатором \"{$identified}\" уже существует.", "identified");}
		
		return true;
	}
	
	/*--------------------------------------------*/
	
	/**
	 * Выборка списка полей по сущности
	 * 
	 * @param int $entity_id
	 * @return array
	 */
	public static function select_list_by_entity_id($entity_id)
	{
		ZN_Entity::is_id($entity_id);
		
		$query = 
<<<SQL
SELECT "f"."ID", "f"."Identified", "f"."Name", "t"."Identified" as "type_identified", 
	   "f"."Null"::int, "f"."Default"
FROM "field" as "f", "field_type" as "t"
WHERE "f"."Entity_ID" = $1
AND "f"."Type_ID" = "t"."ID"
ORDER BY "f"."Sort" ASC
SQL;
		$field = Reg::db_creator()->query_assoc($query, $entity_id, array("field","field_type"));
		
		return $field;
	}
	
	/**
	 * Выборка строки сущности
	 * 
	 * @param int $id
	 * @return bool
	 */
	public static function select_line_by_id($id)
	{
		self::is_id($id);
		
		$query = 
<<<SQL
SELECT "ID", "Name", "Identified", "Type_ID", "Desc", "Null"::int , "Default", "Foreign_ID", "Entity_ID"
FROM "field"
WHERE "ID" = $1
SQL;
		$field = Reg::db_creator()->query_line($query, $id, "field");
		
		return $field;
	}
	
	/**
	 * Выборка всех полей подходящих для внешнего связывания
	 * 
	 * @param int $pack_id
	 * @param int $id
	 * @return array
	 */
	public static function select_foreign_pack_id($pack_id)
	{
		ZN_Pack::is_id($pack_id);
		
		/* Данные */
		$query = 
<<<SQL
SELECT "ID", "Name", "Identified"
FROM "entity"
WHERE "Pack_ID" = $1
ORDER BY "Name" ASC
SQL;
        $entity = Reg::db_creator()->query_assoc($query, $pack_id, "entity");
		
		$query = 
<<<SQL
SELECT "f"."ID", "f"."Name", "f"."Identified", "f"."Entity_ID"
FROM "field" as "f", "entity" as "e", "field_type" as "t"
WHERE "f"."Entity_ID" = "e"."ID"
AND "e"."Pack_ID" = $1
AND "f"."Type_ID" = "t"."ID"
AND "t"."Identified" = 'id'
ORDER BY "f"."Sort" ASC
SQL;
        $field = Reg::db_creator()->query_assoc($query, $pack_id, array("field","entity"));
		
		/* Вывод */
		$data = $entity;
		foreach ($data as $d_key=>$d_val)
		{
			foreach ($field as $f_key=>$f_val)
			{
				if($d_val['ID'] == $f_val['Entity_ID'])
				{
					$data[$d_key]['field'][] = $field[$f_key];
				}
			}
		}
		
		return $data;
	}
}
?>