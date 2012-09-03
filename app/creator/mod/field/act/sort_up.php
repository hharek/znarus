<?php
ZN_Field::sort_up($_GET['id']);
$field = ZN_Field::select_line_by_id($_GET['id']);
redirect("/".Reg::url_creator()."/field/list/?entity_id={$field['Entity_ID']}");
?>
