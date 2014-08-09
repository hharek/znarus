<?php
usleep(100000);

$zn_title = "";
$zn_path = array();
$zn_exe = "";
$zn_css = "";
$zn_js = "";
$zn_admin_html = "";

$mess_ok = "";
$redirect = "";
$reload = false;

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
	
	$admin_identified = Reg::act();
	if(mb_substr($admin_identified, -5) === "_post")
	{$admin_identified = mb_substr(Reg::act(), 0, -5);}
	
	/*** ---------------------------- Проверка привилегий ----------------------- ***/
	/* Проверка на наличие админки */
	$query = 
<<<SQL
SELECT 
	"a"."ID",
	"a"."Name",
	"a"."Identified",
	"a"."Window"::int,
	"a"."Allow_All"::int,
	"m"."ID" as "Module_ID",
	"m"."Name" as "Module_Name",
	"m"."Identified" as "Module_Identified",
	"m"."Type" as "Module_Type"
FROM 
	"admin" as "a",
	"module" as "m"
WHERE 
	"a"."Identified" = $1 AND
	"a"."Module_ID" = "m"."ID" AND
	"m"."Identified" = $2
SQL;
	$admin = Reg::db_core()->query_line($query, [$admin_identified,Reg::mod()], ["admin","module"]);
	if(empty($admin))
	{throw new Exception("Админки «".Reg::mod()."/".Reg::act()."» не существует.");}
	Reg::module_id($admin['Module_ID']);
	Reg::module_type($admin['Module_Type']);
	
	$user = ZN_User_Action::data();
	
	/* Привилегия */
	if($user['Email'] !== "root" and $admin['Allow_All'] !== "1")
	{
		$query = 
<<<SQL
SELECT 
	COUNT(*) as "count"
FROM 
	"user_priv"
WHERE 
	"Admin_ID" = $1 AND
	"Group_ID" = $2
SQL;
		$count = Reg::db_core()->query_one($query, [$admin['ID'], $user['Group_ID']], "user_priv");
		if($count === "0")
		{throw new Exception("Нет доступа");}
	}
	
	/*** ------------------- Исполнение модуля сборочной (начало) -------------- ***/
	/* Помещаем в функцию чтобы закрыть глобальные переменные */
	ob_start();
	call_user_func(function ()
	{
		/* Полезные функции */
		require Reg::path_admin() . "/function.php";
		require Reg::path_admin() . "/packjs/function.php";
		
		try 
		{	
			/* Проверка на наличие файла */
			if(!is_file (Reg::path_app() . "/" . Reg::module_type() . "/" . Reg::mod() . "/admin/act/" . Reg::act() . ".php"))
			{
				throw new Exception("Файла " . Reg::module_type() . "/" . Reg::mod() . "/admin/act/" . Reg::act() . ".php не существует.");
			}
			
			/* Проверка на метод POST */
			if(mb_substr(Reg::act(), -5, 5) === "_post" and $_SERVER['REQUEST_METHOD'] !== "POST")				
			{
				throw new Exception("Метод не POST.");
			}
			
			/* Загрузка классов */
			$zn_class = ZN_Phpclass::select_list_by_module_id(Reg::module_id());
			foreach ($zn_class as $val)
			{
				require Reg::path_app() . "/" . Reg::module_type() . "/" . Reg::mod() . "/class/" . strtolower($val['Identified']) . ".php";
			}
			unset($zn_class); unset($val);
			
			/* Выполнение */
			require Reg::path_app() . "/" . Reg::module_type() . "/" . Reg::mod() . "/admin/act/" . Reg::act() . ".php";

			/* Шаблон */
			if(mb_substr(Reg::act(), -5, 5) !== "_post")
			{
				require Reg::path_app() . "/" . Reg::module_type() . "/" . Reg::mod() . "/admin/html/" . Reg::act() . ".html";
			}
		}
		/* Не реагировать на Exception_Form */
		catch (Exception_Form $e)
		{}
		/* На админские исключения реагировать по своему */
		catch (Exception_Admin $e)
		{throw new Exception_Admin($e->getMessage());}
		/* Выкидывать другие исключения */
		catch (Exception $e)
		{throw new Exception($e);}
	});
	$zn_exe = ob_get_contents();
	ob_end_clean();
	/*** ------------------ Исполнение модуля сборочной (конец) ---------------- ***/
	
	/*** ------------------------------- Заголовок ----------------------------- ***/
	if(mb_substr(Reg::act(), -5, 5) !== "_post")
	{$zn_title = $admin['Name'];}
	
	if(Reg::_isset("title"))
	{
		$zn_title = htmlspecialchars(Reg::title());
	}
	
	/*** --------------------------------- Путь -------------------------------- ***/
	if(mb_substr(Reg::act(), -5, 5) !== "_post")
	{
		$zn_path[0] = 
		[
			"name" => $admin['Name'],
			"url" => "#{$admin['Module_Identified']}/{$admin['Identified']}"
		];
	}

	if(Reg::_isset("path"))
	{
		$zn_path = array();
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
	if(is_file(Reg::path_app() . "/" . Reg::module_type() . "/" . Reg::mod() . "/admin/css/" . Reg::act() . ".css"))
	{
		ob_start();
		require Reg::path_app() . "/" . Reg::module_type() . "/" . Reg::mod() . "/admin/css/" . Reg::act() . ".css";
		$zn_css = ob_get_contents();
		ob_end_clean();

		$zn_css = trim(strtr($zn_css, "\n\t", "  "));
	}
	
	/*** --------------------------- Javascript -------------------------------- ***/
	if(is_file(Reg::path_app() . "/" . Reg::module_type() . "/" . Reg::mod() . "/admin/js/" . Reg::act() . ".js"))
	{
		ob_start();
		require Reg::path_app() . "/" . Reg::module_type() . "/" . Reg::mod() . "/admin/js/" . Reg::act() . ".js";
		$zn_js = ob_get_contents();
		ob_end_clean();
		
		$zn_js =
<<<JS
<script id="zn_exe_js">
{$zn_js}
</script>
JS;
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
	
	/*** -------------------------- Перезагрузка ------------------------------- ***/
	if(Reg::_isset("reload"))
	{
		$reload = true;
	}
	
	/*** -------------------- Показать админки по модулю ----------------------- ***/
	$query =
<<<SQL
SELECT 
	"ID",
	"Name",
	"Identified"
FROM 
	"admin"
WHERE 
	"Module_ID" = $1 AND
	"Visible" = true
ORDER BY
	"Sort" ASC
SQL;
	$zn_admin = Reg::db_core()->query_assoc($query, $admin['Module_ID'], "admin");
	
	$zn_admin_html .= 
<<<HTML
<div class="module">
	<div class="icon icon_install"></div>
	{$admin['Module_Name']}
</div>
HTML;
	
	foreach ($zn_admin as $val)
	{
		$zn_admin_html .= 
<<<HTML
<a class="admin" href="#{$admin['Module_Identified']}/{$val['Identified']}">{$val['Name']}</a>
HTML;
	}
	
}
catch (Exception_Admin $e)
{
	$zn_exe = ob_get_contents();
	ob_end_clean();
	
	echo json_encode
	([
		"exception_admin" => $e->getMessage(),
		"exe" => $zn_exe
	]);
	
	exit();
}
catch (Exception $e)
{
	$zn_exe = ob_get_contents();
	ob_end_clean();
	
	echo json_encode
	([
		"exception" => $e->__toString(),
		"exe" => $zn_exe
	]);
	
	exit();
}

/*** ------------------------------ Вывод json --------------------------- ***/
if($admin['Window'] === "0")
{
	$data = 
	[
		"title" => $zn_title,
		"path" => $zn_path,
		"exe" => $zn_exe,
		"form_error" => Err::get(),
		"css" => $zn_css,
		"js" => $zn_js,

		"mess_ok" => $mess_ok,
		"redirect" => $redirect,
		"reload" => $reload,
		
		"zn_admin_html" => $zn_admin_html
	];
	
	header("Content-type: application/json");
	echo json_encode($data);
	exit();
}

/*** --------------------------- В новом окне ---------------------------- ***/
if($admin['Window'] === "1")
{
	echo $zn_exe;
	exit();
}
?>