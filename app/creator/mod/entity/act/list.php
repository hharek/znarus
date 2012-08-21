<?php
/**
 * Список
 */
function _entity_list()
{
	$pack = ZN_Pack::select_line_by_id($_GET['pack_id']);
	$entity = ZN_Entity::select_list_by_pack_id($_GET['pack_id']);
	
	return array("pack"=>$pack, "entity"=>$entity);
}
?>
