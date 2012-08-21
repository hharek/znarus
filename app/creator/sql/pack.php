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
	 * @return boolean 
	 */
	public static function edit($id, $identified)
	{
		$entity = ZN_Entity::select_list_by_pack_id($id);
		if(!empty($entity))
		{
			foreach ($entity as $val)
			{
				ZN_SQL_Entity::change_pack_identified($val['ID'], $identified);
			}
		}
		
		return true;
	}
}
?>
