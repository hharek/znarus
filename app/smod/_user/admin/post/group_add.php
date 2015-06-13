<?php
$group = _User_Group::add($_POST['Name']);

mess_ok("Группа «{$group['Name']}» добавлена успешно.");
redirect("#_user/user");
?>