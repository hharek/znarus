<?php
ZN_Enum::delete($_GET['id']);
redirect("/".Reg::url_creator()."/enum/list/?field_id={$enum['Field_ID']}");
?>
