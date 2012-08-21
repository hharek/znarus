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
	 * @return boolean 
	 */
	public static function add($identified, $pack_id)
	{
		$pack = ZN_Pack::select_line_by_id($pack_id);
		
		/* Наименование */
		if($pack['Identified'] == $identified)
		{
			$table_name = $identified;
		}
		else
		{
			$table_name = $pack['Identified']."_".$identified;
		}
		
		/* SQL */
		$query = 
<<<SQL
CREATE TABLE "{$table_name}" ()
SQL;
		Reg::db()->query($query);
		
		
		return true;
	}
	
	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $identified
	 * @return boolean 
	 */
	public static function edit($id, $identified)
	{
		$entity = ZN_Entity::select_line_by_id($id);
		$pack = ZN_Pack::select_line_by_id($entity['Pack_ID']);
		
		/* Наименования таблиц */
		$table_name_old = self::get_name_table($entity['Identified'], $pack['Identified']);
		$table_name = self::get_name_table($identified, $pack['Identified']);
		
		/* SQL */
		if($table_name != $table_name_old)
		{
			$query = 
<<<SQL
ALTER TABLE "{$table_name_old}" RENAME TO "{$table_name}"
SQL;
			Reg::db()->query($query);
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
		
		/* Наименование таблицы */
		$table_name = self::get_name_table($entity['Identified'], $pack['Identified']);
		
		/* SQL */
		$query = 
<<<SQL
DROP TABLE "{$table_name}"
SQL;
		Reg::db()->query($query);
		
		return true;
	}
	
	/**
	 * Сменить наименования при смене идентификатора пакета
	 * 
	 * @param int $id
	 * @param string $pack_new
	 * @return boolean 
	 */
	public static function change_pack_identified($id, $pack_new)
	{
		$entity = ZN_Entity::select_line_by_id($id);
		$pack = ZN_Pack::select_line_by_id($entity['Pack_ID']);
		
		/* Наименование таблиц */
		$table_name_old = self::get_name_table($entity['Identified'], $pack['Identified']);
		$table_name = self::get_name_table($entity['Identified'], $pack_new);
		
		/* SQL */
		if($table_name_old != $table_name)
		{
			$query = 
<<<SQL
ALTER TABLE "{$table_name_old}" RENAME TO "{$table_name}"
SQL;
			Reg::db()->query($query);
		}
		
		return true;
	}
	
	/**
	 * Определить наименование таблицы
	 * 
	 * @param string $entity_identified
	 * @param string $pack_identified 
	 * @return string
	 */
	public static function get_name_table($entity_identified, $pack_identified)
	{
		if($entity_identified == $pack_identified)
		{
			$table_name = $entity_identified;
		}
		else
		{
			$table_name = $pack_identified."_".$entity_identified;
		}
		
		return $table_name;
	}
}
?>
