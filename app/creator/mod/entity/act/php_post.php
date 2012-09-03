<?php
ZN_Code_Entity::create_class($_GET['id'], (bool)$_POST['replace']);
redirect("/".Reg::url_creator()."/entity/list/?pack_id={$entity['Pack_ID']}");
?>