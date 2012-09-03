<?php
ZN_Unique::add($_POST['field_check'], $_GET['entity_id']);
redirect("/".Reg::url_creator()."/field/list/?entity_id=".$_GET['entity_id']);
?>
