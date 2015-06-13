<?php
P::set("_seo", "home_title", $_POST['Home_Title']);
T::set("_seo", "home_description", $_POST['Home_Description']);
P::set("_seo", "home_keywords", $_POST['Home_Keywords']);

P::set("_seo", "404_title", $_POST['404_Title']);
T::set("_seo", "404_description", $_POST['404_Description']);
P::set("_seo", "404_keywords", $_POST['404_Keywords']);

P::set("_seo", "403_title", $_POST['403_Title']);
T::set("_seo", "403_description", $_POST['403_Description']);
P::set("_seo", "403_keywords", $_POST['403_Keywords']);

mess_ok("Сохранено");
?>