<?php
/* Включить chroot */
G::file()->chroot_enable();

G::file()->put($_POST['file'], $_POST['content']);

mess_ok("Файл «{$_POST['file']}» отредактирован");
?>