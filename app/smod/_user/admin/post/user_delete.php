<?php
$user = _User::delete($_GET['id']);

mess_ok("Пользователь «{$user['Email']} ({$user['Name']})» удалён.");
redirect("#_user/user");
?>