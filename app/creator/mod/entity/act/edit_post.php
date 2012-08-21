<?php
/**
 * Редактировать (post)
 */
function _entity_edit_post()
{
	$entity = ZN_Entity::select_line_by_id($_GET['id']);
	ZN_Entity::edit($_GET['id'], $_POST['name'], $_POST['identified'], $_POST['desc']);
	
	redirect("/".Reg::url_creator()."/entity/list/?pack_id={$entity['Pack_ID']}");
}
?>
