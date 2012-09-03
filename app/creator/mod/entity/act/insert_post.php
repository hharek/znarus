<?php
ZN_Data_Entity::insert($entity['ID'], $_POST['count']);
redirect("/".Reg::url_creator()."/entity/list/?pack_id={$entity['Pack_ID']}");
?>
