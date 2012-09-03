<?php
ZN_Entity::edit($_GET['id'], $_POST['name'], $_POST['identified'], $_POST['desc']);
redirect("/".Reg::url_creator()."/entity/list/?pack_id={$entity['Pack_ID']}");
?>
