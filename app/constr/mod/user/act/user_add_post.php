<?php
$user = ZN_User::add($_POST['Name'], $_POST['Email'], $_POST['Password'], $_POST['Group_ID'], $_POST['Active']);

mess_ok("Пользователь «{$user['Email']} ({$user['Name']})» добавлен успешно.");
redirect("#user/list");
menu_top("user");
?>