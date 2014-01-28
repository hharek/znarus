<?php
$group = ZN_User_Group::edit($_GET['id'], $_POST['Name']);

mess_ok("Группа «{$group['Name']}» отредактирована.");
reload();
?>