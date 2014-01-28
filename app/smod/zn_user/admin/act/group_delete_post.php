<?php
$group = ZN_User_Group::delete($_GET['id']);

mess_ok("Группа «{$group['Name']}» удалёна.");
redirect("#zn_user/user");
?>