<?php
ZN_Enum::add($_POST['name'], $_GET['field_id']);
redirect("/".Reg::url_creator()."/enum/list/?field_id={$_GET['field_id']}");
?>
