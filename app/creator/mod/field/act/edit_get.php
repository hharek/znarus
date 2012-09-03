<?php
$field = ZN_Field::select_line_by_id($_GET['id']);
$field['type'] = ZN_Field_Type::get_type($field['Type_ID']);

$type = ZN_Field_Type::select_list();
$entity = ZN_Entity::select_line_by_id($field['Entity_ID']);
$enum = ZN_Enum::select_list_by_field_id($_GET['id']);

if(!in_array($field['type'], array("id","sort","foreign","enum")))
{
	foreach ($type as $key=>$val)
	{
		if(in_array($val['Identified'], array("id","sort","foreign","enum")))
		{
			unset($type[$key]);
			continue;
		}
	}
}

if($field['type'] == "foreign") 
{
	$ref_field = ZN_Field::select_line_by_id($field['Foreign_ID']);
	$ref_entity = ZN_Entity::select_line_by_id($ref_field['Entity_ID']);
	$ref_pack = ZN_Pack::select_line_by_id($ref_entity['Pack_ID']);
}

$fdata = $field;
?>
