<?php
P::set("page", "404_title", $_POST['Title']);
T::set("page", "404_content", $_POST['Content']);

mess_ok("Страница 404 изменена");
reload();
?>