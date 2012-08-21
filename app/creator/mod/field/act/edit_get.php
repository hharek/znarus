<?php
/**
 * Редактировать (GET)
 */
function _field_edit_get()
{
	$field = ZN_Field::select_line_by_id($_GET['id']);
	
	$type = ZN_Field_Type::select_list();
	$entity = ZN_Entity::select_line_by_id($field['Entity_ID']);
	$foreign = ZN_Field::select_foreign_pack_id($entity['Pack_ID']);
	$enum = ZN_Enum::select_list_by_field_id($_GET['id']);
	
	if(!in_array(ZN_Field_Type::get_type($field['Type_ID']), array("id","sort","foreign","enum")))
	{
		foreach ($type as $key=>$val)
		{
			if(in_array($val['Identified'], array("id","sort","foreign","enum")))
			{
				unset($type[$key]);
				continue;
			}
		}
	}
	
	$data = $field;
	$data['type'] = $type;
	$data['entity'] = $entity;
	$data['foreign'] = $foreign;
	$data['enum'] = $enum;
	
	return $data;
}
?>
