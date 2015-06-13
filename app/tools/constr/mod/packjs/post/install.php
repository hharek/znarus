<?php
_Packjs::install($_FILES['Dump_File']['tmp_name']);

mess_ok("Пакет JavaScript установлен.");
redirect("#packjs/list");
?>