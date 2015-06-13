<?php
$group = _User_Group::delete($_GET['id']);

mess_ok("Группа «{$group['Name']}» удалёна.");
redirect("#_user/user");
?>