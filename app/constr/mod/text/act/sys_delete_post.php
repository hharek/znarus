<?php
$text = ZN_Text::delete($_GET['id']);

mess_ok("Системный текст «{$text['Identified']} ({$text['Name']}) удалён.»");
redirect("#text/sys");
?>