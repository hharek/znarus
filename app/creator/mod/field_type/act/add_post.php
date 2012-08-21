<?php
/**
 * Добавить (post)
 */
function _field_type_add_post()
{
	ZN_Field_Type::add($_POST['identified'], $_POST['desc']);
	redirect("/".Reg::url_creator()."/field_type/list/");
}
?>
