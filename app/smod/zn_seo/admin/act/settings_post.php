<?php
P::set("zn_seo", "title_default", $_POST['Title']);
P::set("zn_seo", "keywords_default", $_POST['Keywords']);
P::set("zn_seo", "description_default", $_POST['Description']);

mess_ok("Настройки изменены");
reload();
?>