<?php
P::set("page", "403_title", $_POST['Title']);
T::set("page", "403_content", $_POST['Content']);

mess_ok("Страница 403 изменена");
//reload();
?>