<?php
ZN_Entity::add($_POST['name'], $_POST['identified'], $_POST['desc'], $_GET['pack_id']);
redirect("/".Reg::url_creator()."/entity/list/?pack_id={$_GET['pack_id']}");
?>
