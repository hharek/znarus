<?php
/**
 * Удалить (post)
 */
function _pack_delete_post()
{
	ZN_Pack::delete($_GET['id']);
	redirect("/".Reg::url_creator()."/pack/list/");
}
?>
