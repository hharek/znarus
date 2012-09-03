<?php
$enum = ZN_Enum::select_line_by_id($_GET['id']);
$field = ZN_Field::select_line_by_id($enum['Field_ID']);

$fdata = $enum;
?>
