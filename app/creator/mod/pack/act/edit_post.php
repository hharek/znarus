<?php
ZN_Pack::edit($_GET['id'], $_POST['name'], $_POST['identified']);
redirect("/".Reg::url_creator()."/pack/list/");
?>