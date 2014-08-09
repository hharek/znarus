<?php
P::set("zn_seo", "home_title", $_POST['Title']);
P::set("zn_seo", "home_keywords", $_POST['Keywords']);
P::set("zn_seo", "home_description", $_POST['Description']);

mess_ok("Настройки изменены");
reload();
?>