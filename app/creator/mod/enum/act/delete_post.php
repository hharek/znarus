<?php
/**
 * Удалить (POST)
 */
function _enum_delete_post()
{
	$enum = ZN_Enum::select_line_by_id($_GET['id']);
	ZN_Enum::delete($_GET['id']);
	
	redirect("/".Reg::url_creator()."/enum/list/?field_id={$enum['Field_ID']}");
}
?>
