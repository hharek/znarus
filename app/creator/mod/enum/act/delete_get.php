<?php
/**
 * Удалить (GET)
 */
function _enum_delete_get()
{
	$data = ZN_Enum::select_line_by_id($_GET['id']);
	$data['field'] = ZN_Field::select_line_by_id($data['Field_ID']);
	
	return $data;
}
?>
