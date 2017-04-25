<?php
require "config.php";				/* Конфигурация */
require "explorer.php";				/* Проводник */

try
{
	
	/* Проверка конфигурации */
	ZN_Explorer::config_check();
	
	/* Действие */
	if (empty($_GET['action']) or !in_array($_GET['action'], ["ls","upload","mkdir","rename","rm"]))
	{
		throw new Exception("Укажите действие");
	}

	switch ($_GET['action'])
	{
		/* ----------------------- Закачать файлы ------------------------ */
		case "upload":
		{
			/* Закачать */
			$file_upload = [];
			foreach ($_FILES['file']['name'] as $key => $val)
			{
				ZN_Explorer::upload($_POST['url'], $_POST['type'], $_FILES['file']['tmp_name'][$key], $_FILES['file']['name'][$key]);
				$file_upload[] = $_FILES['file']['name'][$key];
			}

			/* Результат */
			$result = $file_upload;
		}
		break;
	
		/* ------------------------ Создать папку ------------------------ */
		case "mkdir":
		{
			ZN_Explorer::mkdir($_POST['url'], $_POST['name']);
			
			$result = ["dir" => $_POST['name']];
		}
		break;
	
		/* ------------------------ Переименовать ----------------------- */
		case "rename":
		{
			/* Переименовать */
			ZN_Explorer::mv($_POST['url'], $_POST['type'], $_POST['old'], $_POST['name']);

			/* Результат */
			$result = ["result" => true];
		}
		break;
	
		/* -------------------------- Удалить --------------------------- */
		case "rm":
		{
			/* Удалить */
			foreach ($_POST['file'] as $val)
			{
				ZN_Explorer::rm($_POST['url'], $_POST['type'], $val);
			}

			/* Результат */
			$result = $_POST['file'];
		}
		break;
	}
	
	/* JSON */
	header("Content-type: application/json; charset=UTF-8");
	echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
catch (Exception $e)
{
	echo json_encode(["error" => $e->getMessage()]);
}
?>