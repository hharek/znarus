<?php
/**
 * Добавить (GET)
 */
function _field_add_get()
{
	$entity = ZN_Entity::select_line_by_id($_GET['entity_id']);
	$type = ZN_Field_Type::select_list();
	$foreign = ZN_Field::select_foreign_pack_id($entity['Pack_ID']);
	
	/* Нельзя Sort без ID */
	if(!ZN_Field::check_add_sort($entity['ID']))
	{
		foreach ($type as $key=>$val)
		{
			if($val['Identified'] == "sort")
			{
				unset($type[$key]);
				break;
			}
		}
	}
	
	/* Только одно поле ID */
	if(!ZN_Field::check_add_id($entity['ID']))
	{
		foreach ($type as $key=>$val)
		{
			if($val['Identified'] == "id")
			{
				unset($type[$key]);
				break;
			}
		}
	}
	
	/* Данные */
	$data = array
	(
		"name" => "",
		"identified" => "",
		"type_id" => "",
		"desc" => "",
		"null" => "0",
		"default" => "",
		"foreign_id" => "",
		
		"entity" => $entity,
		"type" => $type,
		"foreign" => $foreign
	);
	
	return $data;
}
?>