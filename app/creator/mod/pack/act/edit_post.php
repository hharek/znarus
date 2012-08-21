<?php
/**
 * Редактировать (post)
 */
function _pack_edit_post()
{
	ZN_Pack::edit($_GET['id'], $_POST['name'], $_POST['identified']);
	redirect("/".Reg::url_creator()."/pack/list/");
}
?>