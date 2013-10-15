<?php
$group = ZN_User_Group::delete($_GET['id']);

mess_ok("Группа «{$group['Name']}» удалёна.");
redirect("#user/list");
menu_top("user");
?>