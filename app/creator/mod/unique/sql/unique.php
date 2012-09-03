<?php
/**
 * SQL для сущностей 
 */
class ZN_SQL_Unique
{
	/**
	 * Добавить уникальный ключ
	 * 
	 * @param array $field_id_ar
	 * @param int $entity_id
	 * @return boolean 
	 */
	public static function add($field_id_ar, $entity_id)
	{
		$entity = ZN_Entity::select_line_by_id($entity_id);
		
		$constraint_name = $entity['Table']."_UN";
		$field_identified = array();
		foreach ($field_id_ar as $val)
		{
			$field = ZN_Field::select_line_by_id($val);
			$constraint_name .= "_".$field['Identified'];
			$field_identified[] = $field['Identified'];
		}
		$sql_field = "\"".implode("\",\"", $field_identified)."\"";
		
		$query = 
<<<SQL
ALTER TABLE "{$entity['Table']}"
	ADD CONSTRAINT "{$constraint_name}" UNIQUE ({$sql_field})
SQL;
		Reg::db()->query($query);
		
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
		$entity = ZN_Entity::select_line_by_id(ZN_Unique::select_entity_id_by_id($id));
		$field = ZN_Unique::select_list_field_by_unique($id);
		
		$constraint_name = $entity['Table']."_UN";
		foreach ($field as $val)
		{
			$constraint_name .= "_".$val['Identified'];
		}
		
		$query = 
<<<SQL
ALTER TABLE "{$entity['Table']}"
	DROP CONSTRAINT "{$constraint_name}"
SQL;
		Reg::db()->query($query);
		
		return true;
	}
}
?>
