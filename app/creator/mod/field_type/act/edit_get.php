<?php
/**
 * Редактировать (get)
 */
function _field_type_edit_get()
{
	return ZN_Field_Type::select_line_by_id($_GET['id']);
}
?>
