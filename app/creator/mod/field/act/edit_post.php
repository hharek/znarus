<?php
/**
 * Редактировать (POST)
 */
function _field_edit_post()
{
	$field = ZN_Field::select_line_by_id($_GET['id']);
	ZN_Field::edit($_GET['id'], $_POST['name'], $_POST['identified'], $_POST['type_id'], $_POST['desc'], $_POST['null'], $_POST['default'], $_POST['foreign_id']);
	
	redirect("/".Reg::url_creator()."/field/list/?entity_id={$field['Entity_ID']}");
}
?>
