<?php
/**
 * SQL для пакетов
 */
class ZN_SQL_Pack
{
	/**
	 * Редактирование
	 * 
	 * @param int $id
	 * @param string $identified
	 * @return bool 
	 */
	public static function edit($id, $identified)
	{
		$sql = "";
		$pack = ZN_Pack::select_line_by_id($id);
		if($pack['Identified'] != $identified)
		{
			$entity = ZN_Entity::select_list_by_pack_id($id);
			foreach ($entity as $val)
			{
				$table_old = ZN_SQL_Entity::get_table_name($pack['Identified'], $val['Identified']);
				$table = ZN_SQL_Entity::get_table_name($identified, $val['Identified']);
				
				$sql = ZN_SQL_Entity::table_rename($val['ID'], $table_old, $table);
				Reg::db()->multi_query($sql);
				ZN_Entity::set_table($val['ID'], $table);
			}
		}
		
		return true;
	}
}
?>
