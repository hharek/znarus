<?php
ZN_Unique::is_id($_GET['id']);
$unique = array
(
	"id" => $_GET['id'],
	"name" => ZN_Unique::get_name($_GET['id'])
);
$entity = ZN_Entity::select_line_by_id(ZN_Unique::select_entity_id_by_id($unique['id']));
?>
