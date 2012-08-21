<?php
/**
 * Редактировать (get)
 */
function _pack_edit_get()
{
	return ZN_Pack::select_line_by_id($_GET['id']);
}
?>
