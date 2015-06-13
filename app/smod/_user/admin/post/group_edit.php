<?php
$group = _User_Group::edit($_GET['id'], $_POST['Name']);

mess_ok("Группа «{$group['Name']}» отредактирована.");
?>