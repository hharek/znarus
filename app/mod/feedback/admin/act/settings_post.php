<?php
P::set("feedback", "email", $_POST['email']);
P::set("feedback", "from_name", $_POST['from_name']);
P::set("feedback", "subject", $_POST['subject']);

mess_ok("Настройки изменены");
reload();
?>