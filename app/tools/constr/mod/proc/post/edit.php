<?php
$proc = _Proc::edit($_GET['id'], $_POST['Name'], $_POST['Identified'], $_POST['Active']);

mess_ok("proc «{$proc['Identified']} ({$proc['Name']})» отредактирован.");
?>