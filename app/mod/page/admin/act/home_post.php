<?php
P::set("page", "home_title", $_POST['Title']);
T::set("page", "home_content", $_POST['Content']);

mess_ok("Главная страница изменена");
//reload();
?>