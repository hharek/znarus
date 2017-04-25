<?php
switch ($_GET['type'])
{
	case "home":
	{
		P::set("_page", "home_title", $_POST['Home_Title']);
		T::set("_page", "home_content", $_POST['Home_Content']);
		P::set("_page", "home_html_identified", $_POST['Home_Html_Identified']);
		mess_ok("«Главная страница» изменена.");
	}
	break;

	case "404":
	{
		P::set("_page", "404_title", $_POST['404_Title']);
		T::set("_page", "404_content", $_POST['404_Content']);
		P::set("_page", "404_html_identified", $_POST['404_Html_Identified']);
		mess_ok("«Страница 404» изменена.");
	}
	break;
	
	case "403":
	{
		P::set("_page", "403_title", $_POST['403_Title']);
		T::set("_page", "403_content", $_POST['403_Content']);
		P::set("_page", "403_html_identified", $_POST['403_Html_Identified']);
		mess_ok("«Страница 403» изменена.");
	}
	break;
}
?>