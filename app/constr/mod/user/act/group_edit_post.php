<?php
$group = ZN_User_Group::edit($_GET['id'], $_POST['Name']);

mess_ok("Группа «{$group['Name']}» отредактирована.");
require "group_edit.php";
menu_top("user");
?>