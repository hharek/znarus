<?php
/**
 * Редактировать (get)
 */
function _entity_edit_get()
{
	$entity = ZN_Entity::select_line_by_id($_GET['id']);
	$entity['pack'] = ZN_Pack::select_line_by_id($entity['Pack_ID']);
	
	return $entity;
}
?>
