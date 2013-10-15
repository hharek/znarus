<?php
$group = ZN_User_Group::add($_POST['Name']);

mess_ok("Группа «{$group['Name']}» добавлена успешно.");
redirect("#user/list");
menu_top("user");
?>