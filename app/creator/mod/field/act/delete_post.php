<?php
/**
 * Удалить (POST)
 */
function _field_delete_post()
{
	$field = ZN_Field::select_line_by_id($_GET['id']);
	ZN_Field::delete($_GET['id']);
	
	redirect("/".Reg::url_creator()."/field/list/?entity_id={$field['Entity_ID']}");
}
?>
