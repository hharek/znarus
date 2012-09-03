<?php
/**
 * SQL для сущностей 
 */
class ZN_SQL_Entity
{
	/**
	 * Добавить
	 * 
	 * @param string $identified
	 * @param int $pack_id
	 * @return string 
	 */
	public static function add($identified, $pack_id)
	{
		$pack = ZN_Pack::select_line_by_id($pack_id);
		
		/* Наименование */
		$table = self::get_table_name($pack['Identified'], $identified);
		
		/* SQL */
		$sql = 
<<<SQL
CREATE TABLE "{$table}" ();
SQL;
		return $sql;
	}
	
	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $identified
	 * @return string 
	 */
	public static function edit($id, $identified)
	{
		$entity = ZN_Entity::select_line_by_id($id);
		$pack = ZN_Pack::select_line_by_id($entity['Pack_ID']);
		
		$sql = "";
		if($entity['Identified'] != $identified)
		{
			$table_old = self::get_table_name($pack['Identified'], $entity['Identified']);
			$table = self::get_table_name($pack['Identified'], $identified);
			
			$sql = self::table_rename($id, $table_old, $table);
		}
		
		return $sql;
	}
	
	/**
	 * Удалить
	 * 
	 * @param int $id
	 * @return string 
	 */
	public static function delete($id)
	{
		$entity = ZN_Entity::select_line_by_id($id);
		
		/* SQL */
		$sql = 
<<<SQL
DROP TABLE "{$entity['Table']}";
SQL;
		
		return $sql;
	}
	
	/**
	 * Дать наименование таблице
	 * 
	 * @param string $pack_identified 
	 * @param string $entity_identified
	 * @return string
	 */
	public static function get_table_name($pack_identified, $entity_identified)
	{
		if($pack_identified == $entity_identified)
		{
			$table_name = $entity_identified;
		}
		else
		{
			$table_name = $pack_identified."_".$entity_identified;
		}
		
		return $table_name;
	}
	
	/**
	 * Переименовка таблицы
	 * 
	 * @param int $id
	 * @param string $table_old
	 * @param string $table
	 * @return string 
	 */
	public static function table_rename($id, $table_old, $table)
	{
		$entity = ZN_Entity::select_line_by_id($id);
		$field = ZN_Field::select_list_by_entity_id($entity['ID']);
		
		$sql = "";
		
		/***----------SEQUENCE----------***/
		$field_id = array("id" => 0,"identified" => "");
		foreach ($field as $val)
		{
			if($val['type_identified'] == "id")
			{
				$field_id['id'] = $val['ID'];
				$field_id['identified'] = $val['Identified'];
			}
		}

		if($field_id['id'] != 0)
		{
			$sql .= 
<<<SQL
\n\n/* Счётчик */
ALTER SEQUENCE "{$table_old}_seq" RENAME TO "{$table}_seq";
SQL;
		}

		/***-------------PRIMARY------------***/
		if($field_id['id'] != 0)
		{
			$sql .= "\n\n/* PRIMARY */" . ZN_SQL_Constraint::primary_rename($field_id['id'], ZN_SQL_Constraint::primary_get_name($table));
		}
		
		/***--------------FOREIGN-----------***/
		$sql .= "\n\n/* FOREIGN */";
		foreach ($field as $val)
		{
			if($val['type_identified'] == "foreign")
			{
				$sql .= ZN_SQL_Constraint::foreign_rename($val['ID'], ZN_SQL_Constraint::foreign_get_name($table, $val['Identified']));
			}
		}
		
		/***---------TYPE (enum)----------***/
		$sql .= "\n\n/* TYPE */";
		foreach ($field as $val)
		{
			if($val['type_identified'] == "enum")
			{
				$sql .= ZN_SQL_Enum::rename($val['ID'], ZN_SQL_Enum::get_name($table, $val['Identified']));
			}
		}

		/***---------UNIQUE--------***/
		$sql .= "\n\n/* UNIQUE */";
		$unique = ZN_Unique::select_list_by_entity_id($entity['ID']);
		foreach ($unique as $val)
		{
			$field_id_ar = ZN_Unique::select_column_field_by_unique($val['id']);
			$sql .= ZN_SQL_Constraint::unique_rename($val['id'], ZN_SQL_Constraint::unique_get_name($field_id_ar, $table));
		}
		
		/***-----------TABLE-------------***/
		$sql .= 
<<<SQL
\n\n/* Таблица */
ALTER TABLE "{$table_old}" RENAME TO "{$table}";
SQL;
			
		return $sql;
	}
}
?>
