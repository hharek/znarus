<?php
$text = _Text::add($_POST['Name'], $_POST['Identified'], $_POST['Value'], 0);

mess_ok("Системный текст «{$text['Identified']} ({$text['Name']})» добавлен.");
redirect("#text/sys");
?>