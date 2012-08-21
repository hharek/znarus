<?php
/**
 * Удалить (get)
 */
function _pack_delete_get()
{
	return ZN_Pack::select_line_by_id($_GET['id']);
}
?>
