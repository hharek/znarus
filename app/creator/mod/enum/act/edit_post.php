<?php
/**
 * Редактировать (POST)
 */
function _enum_edit_post()
{
	$enum = ZN_Enum::select_line_by_id($_GET['id']);
	ZN_Enum::edit($_GET['id'], $_POST['name']);
	
	redirect("/".Reg::url_creator()."/enum/list/?field_id={$enum['Field_ID']}");
}
?>
