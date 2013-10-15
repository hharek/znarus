<?php
/**
 * Отобразить меню
 * 
 * @param string $type
 * @return array
 */
function show_menu($type)
{
	switch ($type)
	{
		/* Модули */
		case "module":
		{
			return array
			(
				"mod" => ZN_Module::select_list("mod"),
				"smod" => ZN_Module::select_list("smod")
			);
		}
		break;
	
		/* Шаблоны */
		case "html":
		{
			return ZN_Html::select_list();
		}
		break;
	
		/* Пользователи */
		case "user":
		{
			$group = ZN_User_Group::select_list();
			$user = ZN_User::select_list();
			
			foreach ($group as $g_key => $g_val)
			{
				$group[$g_key]['user'] = array();
				foreach ($user as $u_key => $u_val)
				{
					if($g_val['ID'] === $u_val['Group_ID'])
					{
						$group[$g_key]['user'][] = $u_val;
						unset($user[$u_key]);
					}
				}
			}
			
			return $group;
		}
		break;
	
		/* Пакеты */
		case "packjs":
		{
			return array();
		}
		break;
	
		/* Библиотеки */
		case "lib":
		{
			return array();
		}
		break;
	}
}

$error = "";
try
{	
	/* Проверка на CSRF */
	if($_GET['token'] !== $_COOKIE['token'])
	{
		throw new Exception("Ошибка CSRF", 1);
	}
	
	/* Класс для работы с БД */
	Reg::db(new ZN_Pgsql(Reg::db_host(), Reg::db_user(), Reg::db_pass(), Reg::db_name(), Reg::db_schema_public(), Reg::db_port(), Reg::db_persistent(), Reg::db_ssl()), true);
	
	Reg::db_core(clone Reg::db(), true);
	Reg::db_core()->set_schema(Reg::db_schema_core());
	
	Reg::_unset("db_host","db_user","db_pass","db_name","db_persistent","db_ssl");
	
	/* Тип */
	$type_all = ["module", "html", "user", "packjs", "lib"];
	if(!in_array($_POST['type'], $type_all) and $_POST['type'] !== "all")
	{
		throw new Exception("Тип для меню задан неверно.");
	}
	
	/* Данные */
	$result = array();
	if($_POST['type'] !== "all")
	{
		$result = show_menu($_POST['type']);
	}
	else
	{
		foreach ($type_all as $val)
		{
			$result[$val] = show_menu($val);
		}
	}
	
}
catch (Exception $e)
{
	$error = $e->getMessage();
}

/* Вывод */
//header("Content-type: application/json");
header("Content-Type: text/plain");
echo json_encode(array
(
	"error" => $error,	
	"result" => $result
));

?>