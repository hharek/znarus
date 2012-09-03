<?php
/**
 * Создание кода для сущности 
 */
class ZN_Code_Entity
{
	/**
	 * Добавить
	 * 
	 * @param string $name
	 * @param string $identified
	 * @param int $pack_id
	 * @return boolean 
	 */
	public static function add($name, $identified, $pack_id)
	{
		$pack = ZN_Pack::select_line_by_id($pack_id);
		$file = "constr/".self::get_file_name($pack['Identified'], $identified).".php";
		
		/* Подготавливаем файл */
		$code_class = Reg::file_app()->get("creator/phpt/class/empty.phpt");
		$code_class = mb_str_replace("{name}", $name, $code_class);
		$code_class = mb_str_replace("{class}", self::get_class_name($pack['Identified'], $identified), $code_class);
		
		/* Записать файл */
		Reg::file_app()->put($file, $code_class);
		
		return true;
	}
	
	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $name
	 * @param string $class_name
	 * @param string $file_name
	 * @return boolean 
	 */
	public static function edit($id, $name, $class_name, $file_name)
	{
		$entity = ZN_Entity::select_line_by_id($id);
		$pack = ZN_Pack::select_line_by_id($entity['Pack_ID']);
		$file = "constr/".self::get_file_name($pack['Identified'], $entity['Identified']).".php";
		$code_class = Reg::file_app()->get($file);
		
		/* Наименование */
		if($entity['Name'] != $name)
		{
			$pos_start = 13;
			$pos_end = mb_strpos($code_class, "\n", $pos_start, "UTF-8");
			$length = $pos_end - $pos_start;
			$name_old = mb_substr($code_class, $pos_start, $length, "UTF-8");
			$code_class = mb_str_replace($name_old, $name, $code_class);
			Reg::file_app()->put($file, $code_class);
		}
		
		/* Переименовка класса */
		if($entity['Identified'] != $identified)
		{
			$class_name_old = "class ".self::get_class_name($pack['Identified'], $entity['Identified'])."\n{";
			$class_name_new = "class ".$class_name."\n{";
			$code_class = mb_str_replace($class_name_old, $class_name_new, $code_class);
			Reg::file_app()->put($file, $code_class);
		}
		
		/* Переименовка файла */
		if($entity['Identified'] != $identified)
		{
			$file_new = "constr/".$file_name.".php";
			Reg::file_app()->mv($file, $file_new);
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
		$entity = ZN_Entity::select_line_by_id($id);
		$pack = ZN_Pack::select_line_by_id($entity['Pack_ID']);
		$file = "constr/".self::get_file_name($pack['Identified'], $entity['Identified']).".php";
		
		Reg::file_app()->rm($file);
		
		return true;
	}
	
	/**
	 * Создать новый класс
	 * 
	 * @param int $id
	 * @param bool $replace
	 * @return boolean 
	 */
	public static function create_class($id, $replace=false)
	{
		$entity = ZN_Entity::select_line_by_id($id);
		$pack = ZN_Pack::select_line_by_id($entity['Pack_ID']);
		
		/* Создать код класса */
		$code = self::code($id);
		
		$file = "constr/".self::get_file_name($pack['Identified'], $entity['Identified']);
		
		/* Заменяем если стоит опция "Заменить старый файл" */
		if($replace)
		{ 
			$file = $file.".php";
		}
		/* Создаём новый если файла не существует */
		elseif(!Reg::file_app()->is_file($file.".php"))
		{
			$file = $file.".php";
		}
		else
		{
			$query = 
<<<SQL
SELECT "MD5_File"
FROM "entity"
WHERE "ID" = $1
SQL;
			$md5_file_old = Reg::db_creator()->query_one($query, $id, "entity");
			
			/* Заменяем если файл не менялся вручную */
			if($md5_file_old == md5(Reg::file_app()->get($file.".php")))
			{
				$file = $file.".php";
			}
			/* Создаём новый файл с перфиксом _new */
			else
			{
				$file = $file."_new.php";
			}
		}
		
		/* Создать */
		Reg::file_app()->put($file, $code);
		
		/* MD5 file */
		$md5_file = md5($code);
		$query = 
<<<SQL
UPDATE "entity"
SET 
	"MD5_File" = $1
WHERE "ID" = $2
SQL;
		Reg::db_creator()->query($query, array($md5_file, $id), "entity", true);
		
		return true;
	}
	
	/**
	 * Получить наименование файла
	 * 
	 * @param string $pack_identified 
	 * @param string $entity_identified
	 * @return string
	 */
	public static function get_file_name($pack_identified, $entity_identified)
	{
		if($pack_identified == $entity_identified)
		{
			$file = $entity_identified;
		}
		else
		{
			$file = $pack_identified."_".$entity_identified;
		}
		
		return $file;
	}
	
	/**
	 * Получить наименование класс
	 * 
	 * @param string $pack_identified 
	 * @param string $entity_identified
	 * @return string
	 */
	public static function get_class_name($pack_identified, $entity_identified)
	{
		if($pack_identified == $entity_identified)
		{
			$class = ucfirst($entity_identified);
		}
		else
		{
			$class = ucfirst($pack_identified)."_".ucfirst($entity_identified);
		}
		
		return $class;
	}
	
	/**
	 * Получить код по классу
	 * 
	 * @param type $id
	 * @return string 
	 */
	public static function code($id)
	{
		$entity = ZN_Entity::select_line_by_id($id);
		$pack = ZN_Pack::select_line_by_id($entity['Pack_ID']);
		
		/***----------Проверка----------***/
		/* Нет поля ID */
		$query = 
<<<SQL
SELECT COUNT(*) as count
FROM "field" as "f", "field_type" as "t"
WHERE "f"."Entity_ID" = $1
AND "f"."Type_ID" = "t"."ID"
AND "t"."Identified" = 'id'
SQL;
		$count = Reg::db_creator()->query_one($query, $entity['ID'], array("field","field_type"));
		if($count < 1)
		{
			throw new Exception_Creator("У сущности \"{$entity['Name']}\" нет поля ID.");
		}
		
		/* Меньше двух полей */
		$query = 
<<<SQL
SELECT COUNT(*) as count
FROM "field" 
WHERE "Entity_ID" = $1
SQL;
		$count = Reg::db_creator()->query_one($query, $entity['ID'], "field");
		if($count < 2)
		{throw new Exception_Creator("Необходимо как минимум два поля.");}
		
		/***---------Код---------***/
		$code = Reg::file_app()->get("creator/phpt/class/full.phpt");
		$code = mb_str_replace("{name}", $entity['Name'], $code);
		$code = mb_str_replace("{class}", ZN_Code_Entity::get_class_name($pack['Identified'], $entity['Identified']), $code);
		$code = mb_str_replace("{function_add}", self::code_function($id, "add"), $code);
		$code = mb_str_replace("{function_edit}", self::code_function($id, "edit"), $code);
		$code = mb_str_replace("{function_delete}", self::code_function_delete($id), $code);
		$code = mb_str_replace("{function_is_id}", self::code_function_is_id($id), $code);
		$code = mb_str_replace("{function_unique}", self::code_function_unique($id), $code);
		$code = mb_str_replace("{function_select}", self::code_function_select($id), $code);
		
		return $code;
	}
	
	/**
	 * Получить код function add
	 * 
	 * @param int $id
	 * @param string $func_type (add|edit)
	 * @return boolean 
	 */
	public static function code_function($id, $func_type)
	{
		$field = ZN_Field::select_list_by_entity_id($id);
		$entity = ZN_Entity::select_line_by_id($id);
		
		/*---------Создаём новый массив полей----------*/
		$field_ar = array();
		foreach ($field as $key=>$val)
		{
			if($val['type_identified'] == "id")
			{
				$id_identified = strtolower($val['Identified']);
				$id_stolb = $val['Identified'];
			}
			
			if(in_array($val['type_identified'], array('id','sort')))
			{continue;}
						
			if($val['Null'] == 0)
			{$null = false;}
			else
			{$null = true;}
			
			$field_ar[] = array
			(
				"id" => $val['ID'],
				"identified" => strtolower($val['Identified']),
				"name" => $val['Name'],
				"type" => $val['type_identified'],
				"null" => $null,
				"foreign_id" => $val['Foreign_ID']
			);
		}
		
		/*------------Код "Вызов функции"-------------*/
		$code = Reg::file_app()->get("creator/phpt/function/".$func_type.".phpt");
		$arg = array(); $param = "";
		
		if($func_type == "edit")
		{ 
			$arg[] = "\${$id_identified}";
			$param .= "\n\t * @param id \${$id_identified}";
		}
		
		foreach ($field_ar as $val)
		{
			$arg[] = "\${$val['identified']}";
			$param .= "\n\t * @param {$val['type']} \${$val['identified']}";
		}
		
		$code = mb_str_replace("{param}", $param, $code);
		$code = mb_str_replace("{arg}", implode(", ", $arg), $code);
		
		/*---------------Код "Проверка"---------------*/
		$code_check_field_all = "";
		if($func_type == "edit")
		{
			$code_check_id = Reg::file_app()->get("creator/phpt/check_field/id.phpt");
			$code_check_id = mb_str_replace("{identified}", $id_identified, $code_check_id);
			$code_check_id_ar = explode("\n", $code_check_id);
			$code_check_id = "\t\t".implode("\n\t\t", $code_check_id_ar);
			$code_check_field_all .= $code_check_id."\n\n";
		}
		
		foreach ($field_ar as $key=>$val)
		{
			/* Ищем шаблон */
			if($val['null'])
			{
				$file_check = $val['type']."_null.phpt";
				$file_check_all = "all_null.phpt";
			}
			else 
			{
				$file_check = $val['type'].".phpt";
				$file_check_all = "all.phpt";
			}
			
			if(Reg::file_app()->is_file("creator/phpt/check_field/".$file_check))
			{$code_check_field = Reg::file_app()->get("creator/phpt/check_field/".$file_check);}
			else 
			{$code_check_field = Reg::file_app()->get("creator/phpt/check_field/".$file_check_all);}
			
			
			/* Замена */
			$code_check_field = mb_str_replace("{identified}", $val['identified'], $code_check_field);
			$code_check_field = mb_str_replace("{name}", $val['name'], $code_check_field);
			$code_check_field = mb_str_replace("{type}", $val['type'], $code_check_field);
			
			/* Foreign */
			if($val['type'] == "foreign")
			{
				$ref_field = ZN_Field::select_line_by_id($val['foreign_id']);
				$ref_entity = ZN_Entity::select_line_by_id($ref_field['Entity_ID']);
				$ref_pack = ZN_Pack::select_line_by_id($ref_entity['Pack_ID']);
				$ref_class = self::get_class_name($ref_pack['Identified'], $ref_entity['Identified']);

				$code_check_field = mb_str_replace("{class}", $ref_class, $code_check_field);
			}
			
			/* Enum */
			if($val['type'] == "enum")
			{
				$enum_ar = ZN_Enum::select_column_by_field_id($val['id']);
				$code_check_field = mb_str_replace("{enum}", "'".implode("', '", $enum_ar)."'", $code_check_field);
			}
			
			/* Добавляем табуляцию */
			$code_check_field_ar = explode("\n", $code_check_field);
			$code_check_field = "\t\t".implode("\n\t\t", $code_check_field_ar);
			
			/* Объединяем */
			if($key != 0)
			{$code_check_field_all .= "\n\n";}
			
			$code_check_field_all .= $code_check_field;
		}
		
		$code_check = Reg::file_app()->get("creator/phpt/other/check.phpt");
		$code_check = mb_str_replace("{check_field}", $code_check_field_all, $code_check);
		$code = mb_str_replace("{check_field}", $code_check, $code);
		
		/*-------------Код "Уникальность"------------*/
		$query = 
<<<SQL
SELECT "Identified"
FROM "field"
WHERE "Entity_ID" = $1
AND "ID" IN
(
	SELECT "Field_ID"
	FROM "unique_field"
)
ORDER BY "Sort" ASC
SQL;
		$field_unique = Reg::db_creator()->query_column($query, $id, array('field','unique_field'));
		
		$code_unique = "";
		if(!empty($field_unique))
		{
			$arg = array(); 
			foreach ($field_unique as $val)
			{$arg[] = "\$".strtolower($val);}
			
			if($func_type == "edit")
			{$arg[] = "\$".$id_identified;}
			
			$code_unique = Reg::file_app()->get("creator/phpt/other/unique.phpt");
			$code_unique = mb_str_replace("{arg}", implode(", ", $arg), $code_unique);
		}
		$code = mb_str_replace("{unique}", $code_unique, $code);
		
		/*----------------Код "Запрос"------------*/
		/* Собрать данные */
		$insert_into_stolb_ar = array(); 
		$values_param_ar = array(); 
		$array_identified_ar = array(); 
		$update_stolb = "";
		$nomer = 1;
		foreach ($field_ar as $val)
		{
			$insert_into_stolb_ar[] = ucfirst($val['identified']);
			$values_param_ar[] = "\$".$nomer;
			
			$array_identified_ar[] = "\$".$val['identified'];
			
			$update_stolb .= "\n\t\"".ucfirst($val['identified'])."\" = \$".$nomer;
			if($nomer != count($field_ar))
			{$update_stolb .= ",";}
			
			$nomer++;
		}
		
		if($func_type == "edit")
		{
			$array_identified_ar[] = "\$".$id_identified;
		}
		
		/* Заменить */
		if($func_type == "add")
		{$code_query = Reg::file_app()->get("creator/phpt/query/insert.phpt");}
		elseif($func_type == "edit")
		{$code_query = Reg::file_app()->get("creator/phpt/query/update.phpt");}
		
		$code_query = mb_str_replace("{table}", $entity['Table'], $code_query);
		$code_query = mb_str_replace("{insert_into_stolb}", "\"".implode("\", \"", $insert_into_stolb_ar)."\"", $code_query);
		$code_query = mb_str_replace("{insert_values}", implode(", ", $values_param_ar), $code_query);
		$code_query = mb_str_replace("{array_identified}", implode(", ", $array_identified_ar), $code_query);
		$code_query = mb_str_replace("{update_stolb}", $update_stolb, $code_query);
		$code_query = mb_str_replace("{id_stolb}", $id_stolb, $code_query);
		$code_query = mb_str_replace("{id_nomer}", "\$".$nomer, $code_query);
		
		if($func_type == "add")
		{$code = mb_str_replace("{query_insert}", $code_query, $code);}
		elseif($func_type == "edit")
		{$code = mb_str_replace("{query_update}", $code_query, $code);}
		
		
		
		return $code;
	}
	
	/**
	 * Получить код function delete
	 * 
	 * @param type $id
	 * @return string 
	 */
	public static function code_function_delete($id)
	{
		$entity = ZN_Entity::select_line_by_id($id);
		
		/* Общее */
		$field_id = ZN_Entity::get_field_id($id);
		
		$code = Reg::file_app()->get("creator/phpt/function/delete.phpt");
		$code = mb_str_replace("{id_identified}", strtolower($field_id['Identified']), $code);
		$code = mb_str_replace("{table}", $entity['Table'], $code);
		$code = mb_str_replace("{id_stolb}", $field_id['Identified'], $code);
		
		/* Удаление зависемостей */
		$code_foreign_delete = "";
		$query = 
<<<SQL
SELECT "Entity_ID", "Foreign_Change"::int
FROM "field" 
WHERE "Foreign_ID" IN
(
	SELECT "f"."ID"
	FROM "field" as "f", "field_type" as "t"
	WHERE "f"."Entity_ID" = $1
	AND "f"."Type_ID" = "t"."ID"
	AND "t"."Identified" = 'id'
)
SQL;
		$ref_field = Reg::db_creator()->query_assoc($query, $id, array('field','field_type'));
		if(!empty($ref_field))
		{
			foreach ($ref_field as $key=>$val)
			{
				$ref_entity = ZN_Entity::select_line_by_id($val['Entity_ID']);
				$ref_pack = ZN_Pack::select_line_by_id($ref_entity['Pack_ID']);

				/* ID зависемой таблицы */
				$query = 
<<<SQL
SELECT "f"."Identified"
FROM "field" as "f", "field_type" as "t"
WHERE "f"."Entity_ID" = $1
AND "f"."Type_ID" = "t"."ID"
AND "t"."Identified" = 'id'
SQL;
				$ref_id_identified = Reg::db_creator()->query_one($query, $ref_entity['ID'], array('field','field_type'));
				
				/* Столбец по которому привязан */
				$query = 
<<<SQL
SELECT "Identified"
FROM "field" 
WHERE "Entity_ID" = $1
AND "Foreign_ID" = $2
SQL;
				$ref_foreign_identified = Reg::db_creator()->query_one($query, array($ref_entity['ID'], $field_id['ID']), "field");

				/* Наименование класса зависемого */
				$ref_class = ZN_Code_Entity::get_class_name($ref_pack['Identified'], $ref_entity['Identified']);
				
				/* Замена */
				if($val['Foreign_Change'] == "0")
				{$phpt_file = "foreign_delete.phpt";}
				else
				{$phpt_file = "foreign_check.phpt";}
				
				$code_foreign_delete_one = Reg::file_app()->get("creator/phpt/other/".$phpt_file);
				$code_foreign_delete_one = mb_str_replace("{ref_id_identified}", $ref_id_identified, $code_foreign_delete_one);
				$code_foreign_delete_one = mb_str_replace("{ref_table}", $ref_entity['Table'], $code_foreign_delete_one);
				$code_foreign_delete_one = mb_str_replace("{ref_foreign_identified}", $ref_foreign_identified, $code_foreign_delete_one);
				$code_foreign_delete_one = mb_str_replace("{id_identified}", strtolower($field_id['Identified']), $code_foreign_delete_one);
				$code_foreign_delete_one = mb_str_replace("{ref_class}", $ref_class, $code_foreign_delete_one);
				$code_foreign_delete_one = mb_str_replace("{ref_entity_name}", $ref_entity['Name'], $code_foreign_delete_one);
				
				if($key != 0)
				{$code_foreign_delete .= "\n\n";}
				
				/* Комментарий удаления */
				if($val['Foreign_Change'] == "0")
				{$code_foreign_delete_one = "/* Удаление {$ref_entity['Name']} */"."\n\t\t".$code_foreign_delete_one;}
				else
				{$code_foreign_delete_one = "/* Поиск зависемых {$ref_entity['Name']} */"."\n\t\t".$code_foreign_delete_one;}
				
				$code_foreign_delete .= "\n\t\t".$code_foreign_delete_one;
			}
			$code_foreign_delete = "\n".$code_foreign_delete."\n";
		}
		$code = mb_str_replace("{foreign_delete}", $code_foreign_delete, $code);
		
		return $code;
	}
	
	/**
	 * Получить код function is_id
	 * 
	 * @param int $id
	 * @return string 
	 */
	public static function code_function_is_id($id)
	{
		$entity = ZN_Entity::select_line_by_id($id);
		$code = Reg::file_app()->get("creator/phpt/function/is_id.phpt");
		
		$field_id = ZN_Entity::get_field_id($id);
		
		$code = mb_str_replace("{id_identified}", strtolower($field_id['Identified']), $code);
		$code = mb_str_replace("{entity_name}", $entity['Name'], $code);
		$code = mb_str_replace("{table}", $entity['Table'], $code);
		
		return $code;
	}
	
	/**
	 * Получить код function _unique
	 * 
	 * @param int $id
	 * @return string 
	 */
	public static function code_function_unique($id)
	{
		$entity = ZN_Entity::select_line_by_id($id);
		
		/* ID текущей таблицы */
		$field_id = ZN_Entity::get_field_id($id);
		
		/* Поля проверяемые на уникальность */
		$query = 
<<<SQL
SELECT "f"."ID", "f"."Identified", "t"."Identified" as "type_identified"
FROM "field" as "f", "field_type" as "t"
WHERE "f"."Entity_ID" = $1
AND "f"."ID" IN
(
	SELECT "Field_ID"
	FROM "unique_field"
)
AND "f"."Type_ID" = "t"."ID"
ORDER BY "f"."Sort" ASC
SQL;
		$field_unique = Reg::db_creator()->query_assoc($query, $id, array('field','unique_field'));
		if(empty($field_unique))
		{return "";}
		
		/* Параметры и аргументы */
		$arg = array(); $param = "";
		foreach ($field_unique as $val)
		{
			$arg[] = "\$".strtolower($val['Identified']);
			$param .= "\n\t * @param {$val['type_identified']} \$".strtolower($val['Identified']);
		}

		$code = Reg::file_app()->get("creator/phpt/function/unique.phpt");
		$code = mb_str_replace("{param}", $param, $code);
		$code = mb_str_replace("{arg}", implode(", ", $arg), $code);
		$code = mb_str_replace("{id_identified}", strtolower($field_id['Identified']), $code);
		
		/* Запросы */
		$query_unique = "";
		$unique = ZN_Unique::select_list_by_entity_id($id);
		foreach ($unique as $key=>$val)
		{
			$query = 
<<<SQL
SELECT "f"."ID", "f"."Name", "f"."Identified", "t"."Identified" as "type_identified"
FROM "unique_field" as "uf", "field" as "f", "field_type" as "t"
WHERE "uf"."Unique_ID" = $1
AND "uf"."Field_ID" = "f"."ID"
AND "f"."Type_ID" = "t"."ID"
ORDER BY "f"."Sort" ASC
SQL;
			$field = Reg::db_creator()->query_assoc($query, $val['id'], array('unique_field','field','field_type'));
			
			$stolb = ""; $array_stolb_ar = array(); $str_param_ar = ""; $field_first = ""; $field_first_name = "";
			$nomer = 1;
			foreach ($field as $f_key => $f_val)
			{
				if($f_key == 0)
				{$stolb .= "WHERE ";}
				else 
				{$stolb .= "\nAND ";}
				$stolb .= "\"{$f_val['Identified']}\" = \${$nomer}";
				
				$array_stolb_ar[] = "\$".strtolower($f_val['Identified']);
				
				if($f_val['type_identified'] != "foreign")
				{
					$str_param_ar[] = "{\$".strtolower($f_val['Identified'])."}";
					
					if(empty($field_first))
					{
						$field_first = "\$".strtolower($f_val['Identified']);
						$field_first_name = $f_val['Name'];
					}
				}
				
				$nomer++;
			}
			
			$query_unique_one = Reg::file_app()->get("creator/phpt/query/unique.phpt");
			
			$query_unique_one = mb_str_replace("{table}", $entity['Table'], $query_unique_one);
			$query_unique_one = mb_str_replace("{stolb}", $stolb, $query_unique_one);
			$query_unique_one = mb_str_replace("{id_identified}", strtolower($field_id['Identified']), $query_unique_one);
			$query_unique_one = mb_str_replace("{id_stolb}", $field_id['Identified'], $query_unique_one);
			$query_unique_one = mb_str_replace("{array_stolb}", implode(", ", $array_stolb_ar), $query_unique_one);
			$query_unique_one = mb_str_replace("{entity_name}", $entity['Name'], $query_unique_one);
			$query_unique_one = mb_str_replace("{str_param}", implode(" ", $str_param_ar), $query_unique_one);
			$query_unique_one = mb_str_replace("{field_first}", $field_first, $query_unique_one);
			$query_unique_one = mb_str_replace("{field_first_name}", $field_first_name, $query_unique_one);
			
			if($key != 0)
			{
				$query_unique .= "\n\n";
			}
			$query_unique .= $query_unique_one;
		}
		
		$code = mb_str_replace("{query_unique}", $query_unique, $code);
		
		return $code;
	}
	
	/**
	 * Получить код function select
	 * 
	 * @param int $id
	 * @return string 
	 */
	public static function code_function_select($id)
	{
		$code = "";
		$entity = ZN_Entity::select_line_by_id($id);
		$field = ZN_Field::select_list_by_entity_id($id);
		$field_id = ZN_Entity::get_field_id($id);
		
		$select_stolb_ar = array(); $field_foreign = array();
		foreach ($field as $key=>$val)
		{ 
			$select_stolb_ar[] = "\"".$val['Identified']."\"";
			
			if($val['type_identified'] == "foreign")
			{$field_foreign[] = $field[$key];}
		}
		
		/***------------------function select_line_by_id--------------***/
		$code_line_by_id = Reg::file_app()->get("creator/phpt/function/select_line_by_id.phpt");
		$code_line_by_id = mb_str_replace("{entity_name}", $entity['Name'], $code_line_by_id);
		$code_line_by_id = mb_str_replace("{id_identified}", strtolower($field_id['Identified']), $code_line_by_id);
		$code_line_by_id = mb_str_replace("{select_stolb}", implode(", ", $select_stolb_ar), $code_line_by_id);
		$code_line_by_id = mb_str_replace("{table}", $entity['Table'], $code_line_by_id);
		$code_line_by_id = mb_str_replace("{id_stolb}", $field_id['Identified'], $code_line_by_id);
		$code .= $code_line_by_id;
		
		/* Столбец сортировки */
		$query = 
<<<SQL
SELECT "Identified"
FROM "field"
WHERE "Entity_ID" = $1
AND "Is_Order" = true
SQL;
		$order_identified = Reg::db_creator()->query_one($query, $entity['ID'], "field");
		$order = "";
		if(!empty($order_identified))
		{$order = "\nORDER BY \"{$order_identified}\" ASC";}
		
		/***------------------function select_list--------------***/
		if(empty($field_foreign))
		{
			$code_list = Reg::file_app()->get("creator/phpt/function/select_list.phpt");
			$code_list = mb_str_replace("{entity_name}", $entity['Name'], $code_list);
			$code_list = mb_str_replace("{select_stolb}", implode(", ", $select_stolb_ar), $code_list);
			$code_list = mb_str_replace("{table}", $entity['Table'], $code_list);
			$code_list = mb_str_replace("{order}", $order, $code_list);
			
			$code .= "\n\n".$code_list;
		}
		/***-----------------function select_list_by--------------***/
		else
		{
			foreach ($field_foreign as $val)
			{
				$query = 
<<<SQL
SELECT "Entity_ID"
FROM "field"
WHERE "ID" = $1
SQL;
				$entity_id = Reg::db_creator()->query_one($query, $val['Foreign_ID'], "field");
				
				$ref_entity = ZN_Entity::select_line_by_id($entity_id);
				$ref_pack = ZN_Pack::select_line_by_id($ref_entity['Pack_ID']);
				
				$code_list_by = Reg::file_app()->get("creator/phpt/function/select_list_by.phpt");
				$code_list_by = mb_str_replace("{ref_entity_name}", $ref_entity['Name'], $code_list_by);
				$code_list_by = mb_str_replace("{foreign_identified}", strtolower($val['Identified']), $code_list_by);
				$code_list_by = mb_str_replace("{ref_class}", ZN_Code_Entity::get_class_name($ref_pack['Identified'], $ref_entity['Identified']), $code_list_by);
				$code_list_by = mb_str_replace("{select_stolb}", implode(", ", $select_stolb_ar), $code_list_by);
				$code_list_by = mb_str_replace("{table}", $entity['Table'], $code_list_by);
				$code_list_by = mb_str_replace("{foreign_stolb}", $val['Identified'], $code_list_by);
				$code_list_by = mb_str_replace("{order}", $order, $code_list_by);
				
				$code .= "\n\n".$code_list_by;
			}
			
		}
		
		return $code;
	}
}
?>
