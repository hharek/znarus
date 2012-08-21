<?php
/**
 * Добавить (post)
 */
function _pack_add_post()
{
	ZN_Pack::add($_POST['name'], $_POST['identified']);
	redirect("/".Reg::url_creator()."/pack/list/");
}
?>
