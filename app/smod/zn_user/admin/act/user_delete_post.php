<?php
$user = ZN_User::delete($_GET['id']);

mess_ok("Пользователь «{$user['Email']} ({$user['Name']})» удалён.");
redirect("#zn_user/user");
?>