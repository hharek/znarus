<?php
ZN_Data_Entity::truncate($entity['ID']);
redirect("/".Reg::url_creator()."/entity/list/?pack_id={$entity['Pack_ID']}");
?>