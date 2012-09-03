<?php
/**
 * Создание кода для пакета 
 */
class ZN_Code_Pack
{
	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $identified
	 * @return boolean
	 */
	public static function edit($id, $identified)
	{
		$pack = ZN_Pack::select_line_by_id($id);
		
		if($pack['Identified'] != $identified)
		{
			$entity = ZN_Entity::select_list_by_pack_id($id);
			foreach ($entity as $val)
			{
				ZN_Code_Entity::edit($val['ID'], $val['Name'], ZN_Code_Entity::get_class_name($identified, $val['Identified']), ZN_Code_Entity::get_file_name($identified, $val['Identified']));
			}
		}
		
		
		return true;
	}
}
?>
