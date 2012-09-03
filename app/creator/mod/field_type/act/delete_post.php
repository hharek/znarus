<?php
ZN_Field_Type::delete($_GET['id']);
redirect("/".Reg::url_creator()."/field_type/list/");
?>