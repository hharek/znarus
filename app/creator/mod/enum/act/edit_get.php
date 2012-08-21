<?php
/**
 * Редактировать (GET)
 */
function _enum_edit_get()
{
	$data = ZN_Enum::select_line_by_id($_GET['id']);
	$data['field'] = ZN_Field::select_line_by_id($data['Field_ID']);
	
	return $data;
}
?>
