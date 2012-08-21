<?php
/**
 * Редактировать (post)
 */
function _field_type_edit_post()
{
	ZN_Field_Type::edit($_GET['id'], $_POST['identified'], $_POST['desc']);
	redirect("/".Reg::url_creator()."/field_type/list/");
}
?>
