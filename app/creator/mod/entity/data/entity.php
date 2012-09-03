<?php
/**
 * Заполить сущность данными 
 */
class ZN_Data_Entity
{
	/**
	 * Добавить строки в сущность
	 * 
	 * @param int $id
	 * @param int $count
	 * @return boolean 
	 */
	public static function insert($id, $count=1)
	{
		$entity = ZN_Entity::select_line_by_id($id);
		
		$count = (int)$count;
		if($count < 1 or $count > 10)
		{Err::add("Необходимо указать кол-во от 1 до 10.", "count");}
		
		Err::exception();
		
		$sql = "";
		for($i=0; $i<$count; $i++)
		{
			$sql .= "\n\n".self::sql_insert($id);
		}
		
		Reg::db()->multi_query($sql, null, $entity['Table']);
		
		return true;
	}
	
	/**
	 * Очистить таблицу
	 * 
	 * @param int $id
	 * @return boolean 
	 */
	public static function truncate($id)
	{
		$entity = ZN_Entity::select_line_by_id($id);
		
		$query = 
<<<SQL
TRUNCATE "{$entity['Table']}" CASCADE;
SQL;
		Reg::db()->multi_query($query, null, $entity['Table']);
		
		return true;
	}
	
	/**
	 * SQL для вставки строки в сущность
	 * 
	 * @param int $id
	 * @return boolean 
	 */
	public static function sql_insert($id)
	{
		$entity = ZN_Entity::select_line_by_id($id);
		$field = ZN_Field::select_list_by_entity_id($id);
		
		if(count($field) < 2)
		{throw new Exception_Creator("Необходимо как минимум два поля.");}
		
		/* Формируем нужный массив */
		$field_ar = array();
		foreach ($field as $key=>$val)
		{
			if(in_array($val['type_identified'], array("id","sort")))
			{continue;}

			$field_ar[] = array
			(
				"id" => $val['ID'],
				"identified" => $val['Identified'],
				"type" => $val['type_identified'],
				"foreign_id" => $val['Foreign_ID']
			);
		}

		/* Получаем данные */
		$stolb = array(); $values = array();
		foreach ($field_ar as $key=>$val)
		{
			/* Enum */
			if ($val['type'] == "enum") 
			{
				$ar = ZN_Enum::select_column_by_field_id($val['id']);
				if(empty($ar))
				{$data = "null";}
				else
				{
					$data = $ar[array_rand($ar)];
				}
			}
			/* Foreign */
			elseif ($val['type'] == "foreign") 
			{
				$ref_field = ZN_Field::select_line_by_id($val['foreign_id']);
				$ref_entity = ZN_Entity::select_line_by_id($ref_field['Entity_ID']);
				
				$query = 
<<<SQL
SELECT "{$ref_field['Identified']}"
FROM "{$ref_entity['Table']}"
SQL;
				$id_column = Reg::db()->query_column($query);
				if(empty($id_column))
				{$data = "null";}
				else
				{
					$data = $id_column[array_rand($id_column)];
				}
				
			}
			/* Простые */
			else
			{
				$data = self::get_data_field($val['type']);
			}
			
			/* Для SQL */
			$stolb[] = $val['identified'];
			if($data == "null")
			{
				$values[] = $data;
			}
			else
			{
				$values[] = "'".Reg::db()->escape($data)."'";
			}
		}
		
		/* SQL */
		$sql_stolb = "\"".implode("\", \"", $stolb)."\"";
		$sql_values = implode(", ", $values);
		$sql = 
<<<SQL
INSERT INTO "{$entity['Table']}"({$sql_stolb})
VALUES ({$sql_values});
SQL;
		
		return $sql;
	}
	
	
	
	/**
	 * Получить значение для поля
	 * 
	 * @param type $type
	 * @return string 
	 */
	public static function get_data_field($type)
	{
		$data = "";
		switch ($type)
		{
			case "blob":
			{
				$data = md5(mt_rand(0, 100000)).mt_rand(rand(0, 100000)).mt_rand(rand(0, 100000));
			}
			break;

			case "bool":
			{
				$data = mt_rand(0, 1);
			}
			break;

			case "date":
			{
				$data = date("d.m.Y", time()+mt_rand(-100000000, 100000000));;
			}
			break;

			case "email":
			{
				$data = self::get_word("latin", mt_rand(4, 10))."@".self::get_word("latin", mt_rand(4, 10)).".".self::get_word("latin", 2);
			}
			break;

			case "file":
			{
				$data = self::get_word("latin", mt_rand(6, 10)).".".self::get_word("latin", 3);
			}
			break;

			case "float":
			{
				$data = mt_rand(10, 10000).".".mt_rand(1, 1000);
			}
			break;

			case "html":
			{
				$ar = unserialize(Reg::file_app()->get("creator/mod/entity/data/html"));
				$data = $ar[array_rand($ar)];
			}
			break;

			case "identified":
			{
				$data = self::get_word("latin", mt_rand(7, 10));
			}
			break;

			case "image":
			{
				$ext_ar = array("gif","png","jpg");
				$data = self::get_word("latin", mt_rand(5, 7)).".".$ext_ar[array_rand($ext_ar)];
			}
			break;

			case "int":
			{
				$data = mt_rand(-10000, 100000);
			}
			break;

			case "md5":
			{
				$data = md5(mt_rand(10, 10000000));
			}
			break;

			case "price":
			{
				$data = mt_rand(10, 100000).".".mt_rand(10, 99);
			}
			break;

			case "string":
			{
				$data = self::get_word("rus", mt_rand(8, 15));
			}
			break;

			case "text":
			{
				$ar = unserialize(Reg::file_app()->get("creator/mod/entity/data/text"));
				$data = $ar[array_rand($ar)];
			}
			break;

			case "timestamp":
			{
				$data = date("Y-m-d H:i:s-u", time()+mt_rand(-1000000000, 1000000000));;
			}
			break;

			case "uint":
			{
				$data = mt_rand(0, 1000000);
			}
			break;

			case "url":
			{
				$data = self::get_word("latin", mt_rand(9, 20));
			}
			break;
		}
				
		return $data;
	}
	
	/**
	 * Получить слово
	 * 
	 * @param string $lang (latin|rus)
	 * @param int $length
	 * @return string
	 */
	public static function get_word($lang, $length)
	{
		$char_latin_ar = array
		(
			'q','w','e','r','t','y','u','i','o','p',
			'a','s','d','f','g','h','j','k','l',
			'z','x','c','v','b','n','m'
		);
		
		$char_rus_ar = array
		(
			'а','б','в','г','д','е','ё','ж','з','и',
			'й','к','л','м','н','о','п','р','с','т',
			'у','ф','х','ц','ч','ш','щ','ъ','ы','ь',
			'э','ю','я'
		);
		
		$word = "";
		for($i=0; $i<$length; $i++)
		{
			if($lang == "latin")
			{
				$word .= $char_latin_ar[array_rand($char_latin_ar)];
			}
			elseif($lang == "rus")
			{
				$word .= $char_rus_ar[array_rand($char_rus_ar)];
			}
		}
		
		return $word;
	}
}
?>
