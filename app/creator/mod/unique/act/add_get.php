<?php
$entity = ZN_Entity::select_line_by_id($_GET['entity_id']);
$field = ZN_Field::select_list_by_entity_id($entity['ID']);

if(!isset($_POST['field_check']))
{$_POST['field_check'] = array();}

if(!empty($field))
{
	foreach ($field as $key=>$val)
	{
		/* Убираем ID, Sort */
		if(in_array($val['type_identified'], array("id","sort","enum")))
		{
			unset($field[$key]);
			continue;
		}
		
		/* Помечаем ранее выбранные поля */
		if(in_array($val['ID'], $_POST['field_check']))
		{
			$field[$key]['check'] = true;
		}
		else
		{
			$field[$key]['check'] = false;
		}
	}
	
}
?>
