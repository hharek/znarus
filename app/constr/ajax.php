<?php
usleep(100000);

$exception = "";

$zn_title = "";
$zn_path = array();
$zn_exe = "";
$zn_css = "";
$zn_js = "";

$menu_top = "";
$mess_ok = "";
$redirect = "";

try
{
	/*** ------------------ Анализ урла и определение модулей ------------------- ***/
	if(count(Reg::url_path_ar()) != 4)
	{
		throw new Exception("Урл задан неверно", 1);
	}
	
	if
	(
		mb_strtolower(Reg::url_path_ar()[2]) != Reg::url_path_ar()[2] or
		mb_strtolower(Reg::url_path_ar()[3]) != Reg::url_path_ar()[3]
	)
	{
		throw new Exception("Урл задан неверно", 2);
	}
	
	if (!Chf::identified(Reg::url_path_ar()[2]) or !Chf::identified(Reg::url_path_ar()[3]))
	{
		throw new Exception( urldecode($_SERVER['REQUEST_URI']) ."Урл задан неверно", 3);
	}
	
	/* Проверка на CSRF */
	if($_GET['token'] !== $_COOKIE['token'])
	{
		throw new Exception("Ошибка CSRF", 4);
	}
	
	Reg::mod(Reg::url_path_ar()[2], true);
	Reg::act(Reg::url_path_ar()[3], true);
	
	/*** ---------------------- Класс для работы с файлами --------------------- ***/
	if(Reg::file_manager() == "sys")
	{
		Reg::file(new ZN_File(Reg::path_www()), true);
		Reg::file_app(new ZN_File(Reg::path_app()), true);
	}
	else
	{
		Reg::file(new ZN_FTP(Reg::ftp_host(), Reg::ftp_user(), Reg::ftp_pass(), Reg::ftp_path_www(), Reg::ftp_port(), Reg::ftp_ssl()), true);
		Reg::file_app(clone Reg::file(), true);
		Reg::file_app()->set_path(Reg::ftp_path_app());
	}
	
	Reg::_unset("ftp_host","ftp_user","ftp_pass","ftp_path_www","ftp_path_app","ftp_port","ftp_ssl");
	
	/*** -------------------------- Класс для работы с базой -------------------- ***/
	Reg::db(new ZN_Pgsql(Reg::db_host(), Reg::db_user(), Reg::db_pass(), Reg::db_name(), Reg::db_schema_public(), Reg::db_port(), Reg::db_persistent(), Reg::db_ssl()), true);
	
	Reg::db_core(clone Reg::db(), true);
	Reg::db_core()->set_schema(Reg::db_schema_core());
	
	Reg::_unset("db_host","db_user","db_pass","db_name","db_persistent","db_ssl");
	
	/*** ------------------- Исполнение модуля сборочной (начало) -------------- ***/
	/* Помещаем в функцию чтобы закрыть глобальные переменные */
	ob_start();
	call_user_func(function ()
	{
		/* Полезные функции */
		require Reg::path_constr() . "/function.php";
		
		try 
		{
			/* Проверка на наличие файла */
			if(!is_file (Reg::path_constr() . "/mod/" . Reg::mod() . "/act/" . Reg::act() . ".php"))
			{
				throw new Exception("Файла mod/" . Reg::mod() . "/act/" . Reg::act() . ".php не существует.");
			}
			
			/* Проверка на метод POST */
			if(mb_substr(Reg::act(), -5, 5) == "_post" and $_SERVER['REQUEST_METHOD'] !== "POST")				
			{
				throw new Exception("Метод не POST.");
			}
			
			/* Выполнение */
			require Reg::path_constr() . "/mod/" . Reg::mod() . "/act/" . Reg::act() . ".php";

			/* Форма */
			if(is_file (Reg::path_constr() . "/mod/" . Reg::mod() . "/html/" . Reg::act() . ".html"))
			{
				require Reg::path_constr() . "/mod/" . Reg::mod() . "/html/" . Reg::act() . ".html";
			}
		}
		/* Не реагировать на Exception_Form */
		catch (Exception_Form $e)
		{}
		
		return true;
	});
	$zn_exe = ob_get_contents();
	ob_end_clean();
	/*** ------------------ Исполнение модуля сборочной (конец) ---------------- ***/
	
	/*** ------------------------------- Заголовок ----------------------------- ***/
	if(Reg::_isset("title"))
	{
		$zn_title = htmlspecialchars(Reg::title());
	}
	
	/*** --------------------------------- Путь -------------------------------- ***/
	if(Reg::_isset("path"))
	{
		if(!is_array(Reg::path()))
		{
			Reg::path(array(Reg::path()));
		}
		
		foreach (Reg::path() as $val)
		{
			if(!preg_match("#([^\[]*)\[(.*)\]#isu", $val, $match))
			{throw new Exception("Путь задан неверно.");}
			
			$zn_path[] = 
			[
				"name" => trim($match[1]),
				"url" => trim($match[2])
			];
		}
	}
	
	/*** --------------------------- CSS -------------------------------- ***/
	if(is_file(Reg::path_constr()."/mod/".Reg::mod()."/css/".Reg::act().".css"))
	{
		ob_start();
		require Reg::path_constr()."/mod/".Reg::mod()."/css/".Reg::act().".css";
		$zn_css = ob_get_contents();
		ob_end_clean();

		$zn_css = trim(strtr($zn_css, "\n\t", "  "));
	}
	
	/*** --------------------------- Javascript -------------------------------- ***/
	if(is_file(Reg::path_constr()."/mod/".Reg::mod()."/js/".Reg::act().".js"))
	{
		ob_start();
		require Reg::path_constr()."/mod/".Reg::mod()."/js/".Reg::act().".js";
		$zn_js = ob_get_contents();
		ob_end_clean();
		
		$zn_js =
<<<JS
<script id="exe_js">
{$zn_js}
</script>
JS;
	}
	
	/*** ----------------------- Обновить верхнее меню ------------------------- ***/
	if(Reg::_isset("menu_top"))
	{
		$menu_top = Reg::menu_top();
	}
	
	/*** ---------------- Сообщение об удачном выполнении ---------------------- ***/
	if(Reg::_isset("mess_ok"))
	{
		$mess_ok = Reg::mess_ok();
	}
	
	/*** ---------------------------- Редирект --------------------------------- ***/
	if(Reg::_isset("redirect"))
	{
		$redirect = Reg::redirect();
	}
	
}
catch (Exception $e)
{
//	$exception = "Ошибка: ".$e->getMessage().". Код: ".$e->getCode();
//	$exception = $e->__toString();
	$exception = $e->getMessage();
}

/*** ------------------------------ Вывод json --------------------------- ***/
header("Content-type: application/json");
//header("Content-Type: text/plain");
echo json_encode(array
(
	"exception" => $exception,	
	
	"title" => $zn_title,
	"path" => $zn_path,
	"exe" => $zn_exe,
	"form_error" => Err::get(),
	"css" => $zn_css,
	"js" => $zn_js,
	
	"menu_top" => $menu_top,
	"mess_ok" => $mess_ok,
	"redirect" => $redirect
));
?>