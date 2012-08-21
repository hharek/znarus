<?php
function _field_sort_down()
{
	$field = ZN_Field::select_line_by_id($_GET['id']);
	ZN_Field::sort_down($_GET['id']);
	
	redirect("/".Reg::url_creator()."/field/list/?entity_id={$field['Entity_ID']}");
}
?>
