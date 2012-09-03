<?php
ZN_Enum::sort_up($_GET['id']);
$enum = ZN_Enum::select_line_by_id($_GET['id']);
redirect("/".Reg::url_creator()."/enum/list/?field_id={$enum['Field_ID']}");
?>
