<?php
$entity = ZN_Entity::select_line_by_id($_GET['entity_id']);
$type = ZN_Field_Type::select_list();
$foreign = ZN_Field::select_foreign_pack_id($entity['Pack_ID'], $entity['ID']);

/* Остальные внешние ключи */
$query = 
<<<SQL
SELECT "f"."ID", "f"."Name", "f"."Identified", "f"."Entity_ID", 
       "e"."Name" as "Entity_Name", "e"."Identified" as "Entity_Identified",
       "p"."ID" as "pack_id", "p"."Identified" as "pack_identified", 
       "p"."Name" as "pack_name"
FROM "field" as "f", "entity" as "e", "field_type" as "t", "pack" as "p"
WHERE "f"."Entity_ID" = "e"."ID"
AND "e"."Pack_ID" != $1
AND "f"."ID" NOT IN
(
	SELECT "Foreign_ID"
	FROM "field"
	WHERE "Entity_ID" = $2
	AND "Foreign_ID" IS NOT NULL 
)
AND "f"."Type_ID" = "t"."ID"
AND "t"."Identified" = 'id'
AND "e"."Pack_ID" = "p"."ID"
ORDER BY "p"."Name" ASC, "f"."Sort" ASC
SQL;
$foreign_field_all = Reg::db_creator()->query_assoc($query, array($entity['Pack_ID'], $entity['ID']), array("field","entity","field_type","pack"));
$foreign_all = array(); $pack_id_ar = array();
foreach ($foreign_field_all as $p_key=>$p_val)
{
	if(!in_array($p_val['pack_id'], $pack_id_ar))
	{
		$pack_id_ar[] = $p_val['pack_id'];
		$field_ar = array();
		foreach ($foreign_field_all as $e_key=>$e_val)
		{
			if($p_val['pack_id'] == $e_val['pack_id'])
			{
				$field_ar[] = array
				(
					"id" => $e_val['ID'],
					"name" => $e_val['Name'],
					"identified" => $e_val['Identified'],
					"entity_id" => $e_val['Entity_ID'],
					"entity_name" => $e_val['Entity_Name'],
					"entity_identified" => $e_val['Entity_Identified']
				);
			}
		}
		
		$foreign_all[] = array
		(
			"id" => $p_val['pack_id'],
			"name" => $p_val['pack_name'],
			"identified" => $p_val['pack_identified'],
			"field" => $field_ar
		);
	}
}

/* Нельзя Sort без ID */
if(!ZN_Field::check_add_sort($entity['ID']))
{
	foreach ($type as $key=>$val)
	{
		if($val['Identified'] == "sort")
		{
			unset($type[$key]);
			break;
		}
	}
}

/* Только одно поле ID */
if(!ZN_Field::check_add_id($entity['ID']))
{
	foreach ($type as $key=>$val)
	{
		if($val['Identified'] == "id")
		{
			unset($type[$key]);
			break;
		}
	}
}

/* Данные */
$fdata = array
(
	"name" => "",
	"identified" => "",
	"type_id" => "",
	"desc" => "",
	"null" => "0",
	"default" => "",
	"foreign_id" => "",
	"foreign_change" => "0",
	"is_order" => "0"
);
?>