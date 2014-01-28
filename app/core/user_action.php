<?php
/**
 * Действия пользователя
 */
class ZN_User_Action
{
	/**
	 * Авторизация
	 * 
	 * @return bool
	 * @param string $email
	 * @param string $password
	 */
	public static function auth($email, $password)
	{
		/* Проверка на root */
		if($email === Reg::root_name() and $password === Reg::root_password())
		{
			self::session_create_root();
			return true;
		}
		
		/* Проверка E-mail */
		$email = trim($email);
		
		if(mb_strlen($email) === 0)
		{throw new Exception_Form("«E-mail» не задан.");}
		
		if(!Chf::email($email))
		{throw new Exception_Form("«E-mail» задан неверно. " . Chf::error());}
		
		/* Проверка пароля */
		$password = trim($password);
		
		if(mb_strlen($password) === 0)
		{throw new Exception_Form("Пароль не задан.");}
		
		if(!Chf::string($password))
		{throw new Exception_Form("Пароль задан неверно. " . Chf::error());}
		
		if(mb_strlen($password) < Reg::password_length_min())
		{throw new Exception_Form("Пароль не должен быть меньше " . Reg::password_length_min() . " символов.");}
		
		if(mb_strlen($password) > Reg::password_length_max())
		{throw new Exception_Form("Пароль не должен быть больше " . Reg::password_length_max() . " символов.");}
		
		$password = ZN_User::password_hash($password);
		
		/* Проверка по БД */
		$query = 
<<<SQL
SELECT
	"ID"
FROM 
	"user"
WHERE 
	"Active" = true AND
	"Email" = $1 AND
	"Password" = $2
SQL;
		$user_id = Reg::db_core()->query_one($query, array($email, $password), "user");
		if(empty($user_id))
		{throw new Exception_Form("E-mail и пароль заданы неверно.");}
		
		/* Создание сессии */
		self::session_create($user_id);
		return true;
	}
	
	/**
	 * Выход
	 */
	public static function logout()
	{
		self::session_delete();
	}
	
	/**
	 * Создать сессию
	 * 
	 * @param int $user_id
	 */
	public static function session_create($user_id)
	{
		/* Проверка */
		$user = ZN_User::select_line_by_id($user_id);
		if($user['Active'] === '0')
		{throw new Exception_Admin("Пользователь не активирован.");}
		
		/* Удаление старый сессий */
		$query = 
<<<SQL
SELECT 
	"ID"
FROM 
	"user_session"
WHERE 
	"User_ID" = $1
SQL;
		$sid_old = Reg::db_core()->query_column($query, $user_id, "user_session");
		if(!empty($sid_old))
		{
			foreach ($sid_old as $val)
			{
				Reg::db_core()->delete("user_session", array("ID" => $val));
			}
		}
		
		/* Добавить */
		$data = 
		[
			"ID" => md5(microtime() . mt_rand(0, 1000000)), 
			"IP" => $_SERVER['REMOTE_ADDR'], 
			"Browser" => $_SERVER['HTTP_USER_AGENT'], 
			"User_ID" => $user_id
		];
		$sid = Reg::db_core()->insert("user_session", $data, "ID");
		
		/* Создание сессии */
		setcookie("sid", $sid, time() + Reg::session_time_admin(), "/" . urlencode(Reg::url_admin()), null, false, true);
	
		/* Создание токена */
		$token = md5(microtime(true) + mt_rand(1, 1000000));
		setcookie("token", $token, time() + Reg::session_time_admin(), "/" . urlencode(Reg::url_admin()));
	}
	
	/**
	 * Создать сессию для root-а
	 */
	public static function session_create_root()
	{
		/* Удаление старый сессий */
		$query = 
<<<SQL
SELECT 
	"ID"
FROM 
	"user_session"
WHERE 
	"User_ID" IS NULL
SQL;
		$sid_old = Reg::db_core()->query_column($query, null, "user_session");
		if(!empty($sid_old))
		{
			foreach ($sid_old as $val)
			{
				Reg::db_core()->delete("user_session", array("ID" => $val));
			}
		}
		
		/* Добавить */
		$data = 
		[
			"ID" => md5(microtime() . mt_rand(0, 1000000)), 
			"IP" => $_SERVER['REMOTE_ADDR'], 
			"Browser" => $_SERVER['HTTP_USER_AGENT'], 
			"User_ID" => null
		];
		$sid = Reg::db_core()->insert("user_session", $data, "ID");
		
		/* Создание сессии */
		setcookie("sid", $sid, time() + Reg::session_time_admin(), "/" . urlencode(Reg::url_admin()), null, false, true);
	
		/* Создание токена */
		$token = md5(microtime(true) + mt_rand(1, 1000000));
		setcookie("token", $token, time() + Reg::session_time_admin(), "/" . urlencode(Reg::url_admin()));
	}
	
	/**
	 * Проверить
	 * 
	 * @return boolean
	 */
	public static function session_check()
	{
		/* Проверить куки */
		if(empty($_COOKIE['sid']) or empty($_COOKIE['token']))
		{return false;}
		
		/* Данные по сессии */
		$query = 
<<<SQL
SELECT 
	"ID",
	"Date",
	"IP",
	"Browser",
	"User_ID"
FROM 
	"user_session"
WHERE 
	"ID" = $1 
SQL;
		$session = Reg::db_core()->query_line($query, $_COOKIE['sid'], "user_session");
		if(empty($session))
		{return false;}
		
		/* Проверка */
		if(strtotime($session['Date']) < strtotime("-" . Reg::session_time_admin() . "sec"))
		{return false;}
		
		if($session['IP'] !== $_SERVER['REMOTE_ADDR'])
		{return false;}
		
		if($session['Browser'] !== $_SERVER['HTTP_USER_AGENT'])
		{return false;}
		
		return true;
	}
	
	/**
	 * Удалить сессию
	 */
	public static function session_delete()
	{
		if(!empty($_COOKIE['sid']))
		{
			/* Удалить с БД */
			Reg::db_core()->delete("user_session", array("ID" => $_COOKIE['sid']));
		
			/* Удалить куки */
			setcookie("sid", null, time() - 360000, "/" . urlencode(Reg::url_admin()));
			setcookie("token", null, time() - 360000, "/" . urlencode(Reg::url_admin()));
		}
	}
	
	/**
	 * Данные по пользователю
	 * 
	 * @return array
	 */
	public static function data()
	{
		/* Проверка */
		if(empty($_COOKIE['sid']) or empty($_COOKIE['token']))
		{throw new Exception_Admin("sid или token не указан.");}
		
		/* Сессия */
		$query = 
<<<SQL
SELECT 
	"ID",
	"Date",
	"IP",
	"Browser",
	"User_ID"
FROM 
	"user_session"
WHERE 
	"ID" = $1 
SQL;
		$session = Reg::db_core()->query_line($query, $_COOKIE['sid'], "user_session");
		if(empty($session))
		{throw new Exception_Admin("sid указан неверно.");}
		
		/* Данные по пользователю */
		if(!empty($session['User_ID']))
		{
			$query = 
<<<SQL
SELECT 
	"u"."ID", 
	"u"."Name", 
	"u"."Email",
	"u"."Password",
	"g"."ID" as "Group_ID",
	"g"."Name" as "Group_Name"
FROM 
	"user" as "u", 
	"user_group" as "g"
WHERE 
	"u"."Active" = true AND
	"u"."ID" = $1 AND
	"u"."Group_ID" = "g"."ID"
SQL;
			$user = Reg::db_core()->query_line($query, $session['User_ID'], "table");
		}
		else
		{
			$user['ID'] = "0";
			$user['Name'] = "root";
			$user['Email'] = "root";
			$user['Group_ID'] = "0";
			$user['Group_Name'] = "root";
		}
		
		$user['IP'] = $session['IP'];
		$user['Browser'] = $session['Browser'];
		$user['Date'] = $session['Date'];
		
		return $user;
	}
	
	/**
	 * Сменить свой пароль
	 * 
	 * @param string $old
	 * @param string $new
	 */
	public static function passwd($old, $new)
	{
		/* Старый пароль */
		$old = trim($old);
		Err::check_field($old, "string", false, "Password_Old", "Старый пароль");
		if(mb_strlen($old) < Reg::password_length_min())
		{Err::add("Пароль не должен быть меньше " . Reg::password_length_min() . " символов.", "Password_Old");}
		if(mb_strlen($old) > Reg::password_length_max())
		{Err::add("Пароль не должен быть больше " . Reg::password_length_max() . " символов.", "Password_Old");}
		
		/* Новый пароль */
		$new = trim($new);
		Err::check_field($new, "string", false, "Password_New", "Новый пароль");
		if(mb_strlen($new) < Reg::password_length_min())
		{Err::add("Пароль не должен быть меньше " . Reg::password_length_min() . " символов.", "Password_New");}
		if(mb_strlen($new) > Reg::password_length_max())
		{Err::add("Пароль не должен быть больше " . Reg::password_length_max() . " символов.", "Password_New");}
		
		Err::exception();
		
		/* Совпадение паролей */
		if($old === $new)
		{
			Err::add("Старый и новый пароль совпадают.", "Password_Old");
			Err::add("Старый и новый пароль совпадают.", "Password_New");
		}
		
		Err::exception();
		
		/* Проверка старого пароля */
		$user = self::data();
		
		if($user['Name'] === "root")
		{throw new Exception_Admin("Чтобы сменить пароль у root-а, воспользуйтесь конфигурационным файлом.");}
		
		if($user['Password'] !== ZN_User::password_hash($old))
		{
			Err::add("Старый пароль указан неверно.", "Password_Old");
			Err::exception();
		}
		
		/* Сменить пароль */
		$data =
		[
			"Password" => ZN_User::password_hash($new)
		];
		Reg::db_core()->update("user", $data, array("ID" => $user['ID']));
	}
}
?>
