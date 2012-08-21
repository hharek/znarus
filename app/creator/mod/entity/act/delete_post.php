<?php
/**
 * Удалить (post)
 */
function _entity_delete_post()
{
	$entity = ZN_Entity::select_line_by_id($_GET['id']);
	ZN_Entity::delete($_GET['id']);
	
	redirect("/".Reg::url_creator()."/entity/list/?pack_id={$entity['Pack_ID']}");
}
?>
