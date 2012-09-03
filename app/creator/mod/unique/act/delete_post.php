<?php
ZN_Unique::delete($_GET['id']);
redirect("/".Reg::url_creator()."/field/list/?entity_id={$entity['ID']}");
?>
