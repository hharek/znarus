<?php
/**
 * Добавить (POST)
 */
function _field_add_post()
{
	ZN_Field::add($_POST['name'], $_POST['identified'], $_POST['type_id'], $_POST['desc'], $_POST['null'], $_POST['default'], $_POST['foreign_id'], $_GET['entity_id']);
	redirect("/".Reg::url_creator()."/field/list/?entity_id={$_GET['entity_id']}");
}
?>
