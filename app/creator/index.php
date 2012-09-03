<?php
//header("Content-Type: text/plain");

Reg::path_creator(Reg::path_app()."/creator");
//Reg::file_app()->set_path(Reg::file_app()->get_path()."/constr");

/***---------------------Загрузка рисунков и всего остального--------------***/
if(mb_substr(Reg::url_path(), -8, 8, "UTF-8") == "file.php")
{
	/* Проверка */
	if
	(
		!isset($_GET['path']) or
		count($_GET) != 1 or
		!Chf::path($_GET['path']) or
		(
				!in_array(mb_substr($_GET['path'], -4, 4, "UTF-8"), array(".jpg",".png",".gif",".css")) and
				mb_substr($_GET['path'], -3, 3, "UTF-8") != ".js" 
		) or
		!is_file(Reg::path_creator()."/".$_GET['path'])
	)
	{
		throw new Exception_404();
	}
	
	/* Заголовок */
	switch (mb_substr($_GET['path'], -4, 4, "UTF-8"))
	{
		case ".jpg":
		{header("Content-Type: image/jpeg");}
		break;
	
		case ".png":
		{header("Content-Type: image/png");}
		break;
	
		case ".gif":
		{header("Content-Type: image/gif");}
		break;
	
		case ".css":
		{header("Content-Type: text/css");}
		break;
	}
	
	if(mb_substr($_GET['path'], -3, 3, "UTF-8") == ".js")
	{
		header("Content-Type: application/x-javascript");
	}
	
	/* Вывод */
	header("Content-Length: ".filesize(Reg::path_creator()."/".$_GET['path']));
	readfile(Reg::path_creator()."/".$_GET['path']);
	
	exit();
}

/***-------------------Анализ урла и определение модулей сборочной-----------------------***/
if(Reg::url_path() != "/".Reg::url_creator()."/")
{
	if(mb_substr(Reg::url_path(), mb_strlen(Reg::url_path(), "UTF-8")-1, 1, "UTF-8") != "/")
	{throw new Exception_Creator("Урл задан неверно");}

	$path = mb_substr(Reg::url_path(), 1, -1, "UTF-8");
	$path_ar = explode("/", $path);
	unset($path_ar[0]);

	if(count($path_ar) != 2)
	{throw new Exception_Creator("Урл задан неверно");}

	if(empty($path_ar[1]) or !preg_match("#^[a-zA-Z0-9_]+$#isu", $path_ar[1]))
	{throw new Exception_Creator("Урл задан неверно");}
	$path_ar[1] = strtolower($path_ar[1]);
	
	if(empty($path_ar[2]) or !preg_match("#^[a-zA-Z0-9_]+$#isu", $path_ar[2]))
	{throw new Exception_Creator("Урл задан неверно");}
	$path_ar[2] = strtolower($path_ar[2]);

	Reg::mod($path_ar[1], true);
	Reg::act($path_ar[2], true);
}
/* Модуль сборочной по умолчанию */
else
{
	Reg::mod("pack");
	Reg::act("list");
}

/***------------------------Другие классы-----------------------***/
require (Reg::path_creator()."/helper.php");

/***-----------------------Основные классы----------------------***/
require (Reg::path_creator()."/mod/pack/class/pack.php");
require (Reg::path_creator()."/mod/entity/class/entity.php");
require (Reg::path_creator()."/mod/field_type/class/field_type.php");
require (Reg::path_creator()."/mod/field/class/field.php");
require (Reg::path_creator()."/mod/enum/class/enum.php");
require (Reg::path_creator()."/mod/unique/class/unique.php");

/***---------------------------SQL классы----------------------***/
require (Reg::path_creator()."/mod/pack/sql/pack.php");
require (Reg::path_creator()."/mod/entity/sql/entity.php");
require (Reg::path_creator()."/mod/field/sql/field.php");
require (Reg::path_creator()."/mod/enum/sql/enum.php");
require (Reg::path_creator()."/mod/unique/sql/unique.php");
require (Reg::path_creator()."/mod/constraint/sql/constraint.php");

/***----------------------Класс работы с кодом-----------------***/
require (Reg::path_creator()."/mod/pack/code/pack.php");
require (Reg::path_creator()."/mod/entity/code/entity.php");

require (Reg::path_creator()."/mod/entity/data/entity.php");

/***------------Исполнение модуля сборочной (начало)-----------***/
/* Файл действия */
if
(
	!is_file(Reg::path_creator()."/mod/".Reg::mod()."/act/".Reg::act().".php") and
	(
		!is_file(Reg::path_creator()."/mod/".Reg::mod()."/act/".Reg::act()."_get.php") and 
		!is_file(Reg::path_creator()."/mod/".Reg::mod()."/act/".Reg::act()."_post.php")
	)
)
{throw new Exception_Creator("Файла ".Reg::path_creator()."/mod/".Reg::mod()."/act/".Reg::act()."(_get|_post).php не существует.");}

/* Помещаем в функцию чтобы закрыть глобальные переменные */
function exe_mod()
{
	/* Все окна, кроме окон типа form */
	if(is_file(Reg::path_creator()."/mod/".Reg::mod()."/act/".Reg::act().".php"))
	{require (Reg::path_creator()."/mod/".Reg::mod()."/act/".Reg::act().".php");}
	
	/* Окно типа form */
	else
	{
		/***-----GET форма----***/
		require (Reg::path_creator()."/mod/".Reg::mod()."/act/".Reg::act()."_get.php");
		
		/* FDATA - данные формы <input name="pole" value=$fdata['pole'] /> */
		if(isset($fdata) and is_array($fdata))
		{$fdata = array_change_key_case($fdata);}

		/***-----POST форма----***/
		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
//			/* Проверка от CSRF */
//			if($_SESSION['csrf_key'] != $_POST['csrf_key'])
//			{throw new Exception_Creator("Ошибка CSRF");}

			try
			{
				require (Reg::path_creator()."/mod/".Reg::mod()."/act/".Reg::act()."_post.php");
			}
			/* Ошибки пользовательские */
			catch (Exception_User $e)
			{
				Reg::zn_error(Err::get(), true);
			}
			/* Другие ошибки */
			catch (Exception $e)
			{
				Reg::zn_error_common($e->getMessage());
				Reg::zn_error_common_more(nl2br(str_replace("\t", "&nbsp;&nbsp;&nbsp;&nbsp;",$e->__toString())));
			}

			/* POST данные в переменную $fdata */
			if(empty ($fdata) or !is_array($fdata))
			{$fdata = array();}
			
			foreach ($_POST as $key=>$val)
			{
				$key = mb_strtolower($key, "UTF-8");
				$fdata[$key] = $val;
			}
		}
	}

	/* Шаблон */
	if(is_file(Reg::path_creator()."/mod/".Reg::mod()."/tpl/".Reg::act().".phtml"))
	{require (Reg::path_creator()."/mod/".Reg::mod()."/tpl/".Reg::act().".phtml");}
	
	return true;
}

/* Помещаем результат функции переменную через буфер  */
ob_start();
exe_mod();
$zn_exe = ob_get_contents();
ob_end_clean();
/***------------Исполнение модуля сборочной (конец)-----------***/

/* CSRF */
$csrf_key = md5(microtime(true)+mt_rand(1, 100000000));
$_SESSION['csrf_key'] = $csrf_key;

/***----------------------Javascript----------------------***/
$zn_js_content = "";
if(is_file(Reg::path_creator()."/mod/".Reg::mod()."/js/".Reg::act().".js"))
{
	ob_start();
	require (Reg::path_creator()."/mod/".Reg::mod()."/js/".Reg::act().".js");
	$zn_js_content = ob_get_contents();
	ob_end_clean();
	
	$zn_js_content = 
<<<JS
<script type="text/javascript">
$(function()
{

{$zn_js_content}

});
</script>
JS;
}

require (Reg::path_creator()."/tpl/index.phtml");

?>
