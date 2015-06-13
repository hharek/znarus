<?php
/* Главная страница */
if ($_POST['type'] === "home")
{
	_Exe::get_by_identified($_POST['Home_Module'], $_POST['Home_Exe']);
	if (empty($_POST['Home_Admin_Url']))
	{
		throw new Exception("Укажите админку для главной страницы.");
	}
	
	P::set("home_module", $_POST['Home_Module']);
	P::set("home_exe", $_POST['Home_Exe']);
	P::set("home_admin_url", $_POST['Home_Admin_Url']);
}

/* Страница 404 */
if ($_POST['type'] === "404")
{
	_Exe::get_by_identified($_POST['404_Module'], $_POST['404_Exe']);
	if (empty($_POST['404_Admin_Url']))
	{
		throw new Exception("Укажите админку для главной страницы.");
	}
	
	P::set("404_module", $_POST['404_Module']);
	P::set("404_exe", $_POST['404_Exe']);
	P::set("404_admin_url", $_POST['404_Admin_Url']);
}

/* Страница 403 */
if ($_POST['type'] === "403")
{
	_Exe::get_by_identified($_POST['403_Module'], $_POST['403_Exe']);
	if (empty($_POST['403_Admin_Url']))
	{
		throw new Exception("Укажите админку для главной страницы.");
	}

	P::set("403_module", $_POST['403_Module']);
	P::set("403_exe", $_POST['403_Exe']);
	P::set("403_admin_url", $_POST['403_Admin_Url']);
}

/* Сообщение */
mess_ok("Сохранено");
?>