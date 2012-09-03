<?php
ZN_Pack::delete($_GET['id']);
redirect("/".Reg::url_creator()."/pack/list/");
?>
