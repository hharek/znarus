<?php
/**
 * Список перечислений
 */
function _enum_list()
{
	$enum = ZN_Enum::select_list_by_field_id($_GET['field_id']);
	$field = ZN_Field::select_line_by_id($_GET['field_id']);
	$entity = ZN_Entity::select_line_by_id($field['Entity_ID']);
	
	return array("enum" => $enum, "field" => $field, "entity" => $entity);
}
?>
