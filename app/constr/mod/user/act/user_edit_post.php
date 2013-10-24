<?php
$user =  ZN_User::edit($_GET['id'], $_POST['Name'], $_POST['Email'], $_POST['Group_ID'], $_POST['Active']);

mess_ok("Пользователь «{$user['Email']}» отредактирован.");
require "user_edit.php";
menu_top("user");
?>