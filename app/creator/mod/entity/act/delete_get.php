<?php
$entity = ZN_Entity::select_line_by_id($_GET['id']);
$pack = ZN_Pack::select_line_by_id($entity['Pack_ID']);
?>
