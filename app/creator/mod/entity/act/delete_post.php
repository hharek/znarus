<?php
ZN_Entity::delete($_GET['id']);
redirect("/".Reg::url_creator()."/entity/list/?pack_id={$entity['Pack_ID']}");
?>
