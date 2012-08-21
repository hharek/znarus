<?php
/**
 * Добавить (GET)
 */
function _enum_add_get()
{
	$data = array
	(
		"name" => "",
		"field" => ZN_Field::select_line_by_id($_GET['field_id'])
	);
	
	return $data;
}
?>
