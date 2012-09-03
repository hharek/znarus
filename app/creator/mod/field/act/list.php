<?php
$entity = ZN_Entity::select_line_by_id($_GET['entity_id']);
$pack = ZN_Pack::select_line_by_id($entity['Pack_ID']);
$field = ZN_Field::select_list_by_entity_id($entity['ID']);
$unique = ZN_Unique::select_list_by_entity_id($entity['ID']);
?>