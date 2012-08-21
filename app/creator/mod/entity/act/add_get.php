<?php
/**
 * Добавить (get)
 */
function _entity_add_get()
{
	$pack = ZN_Pack::select_line_by_id($_GET['pack_id']);
	
	$data = array
	(
		"name" => "",
		"identified" => "",
		"desc" => "",
		"pack" => $pack
	);
	
	return $data;
}
?>
