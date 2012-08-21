<?php
/**
 * Удалить (GET)
 */
function _field_delete_get()
{
	$field = ZN_Field::select_line_by_id($_GET['id']);
	$entity = ZN_Entity::select_line_by_id($field['Entity_ID']);
	
	$data = $field;
	$data['entity'] = $entity;
	
	return $data;
}
?>
