<?php
ZN_Enum::edit($_GET['id'], $_POST['name']);
redirect("/".Reg::url_creator()."/enum/list/?field_id={$enum['Field_ID']}");
?>
