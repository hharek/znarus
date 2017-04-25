<?php
/**
 * Действия пользователя
 */
class _User_Action
{
	/**
	 * Авторизация в конструкторе
	 * 
	 * @param string $name
	 * @param string $password
	 */
	public static function login_constr($name, $password)
	{
		/* Проверка имени */
		$name = trim((string) $name);
		if ($name === "")
		{
			throw new Exception("Имя не задано.");
		}
		if ($name !== ROOT_NAME)
		{
			throw new Exception("В конструктор можно зайти только под пользователем «" . ROOT_NAME_FULL . "».");
		}
		
		/* Проверка пароля */
		_User::check_password($password);
		if ($password !== ROOT_PASSWORD)
		{
			throw new Exception("Пароль задан неверно.");
		}
		
		/* Создать сессию */
		_User_Session::add("constr", "root", null);
	}
	
	/**
	 * Авторизация
	 * 
	 * @param string $email
	 * @param string $password
	 * @return bool
	 */
	public static function login_admin($email, $password)
	{
		/* root */
		if ($email === ROOT_NAME)
		{
			_User::check_password($password);
			if ($password !== ROOT_PASSWORD)
			{
				throw new Exception("Пароль задан неверно.");
			}
			
			_User_Session::add("admin", "root", null);
		}
		/* Не root */
		elseif ($email !== ROOT_NAME)
		{
			/* Проверка E-mail */
			$email = trim((string) $email);
			$email = strtolower($email);
			if ($email === "")
			{
				throw new Exception("Почтовый ящик не задан.");
			}
			if (!Type::check("email", ($email)))
			{
				throw new Exception("Почтовый ящик задан неверно.");
			}

			/* Поиск пользователя с E-mail */
			$query = 
<<<SQL
SELECT
	"ID",
	"Password",
	"Active"
FROM
	"user"
WHERE
	"Email" = $1
SQL;
			$user = G::db_core()->query($query, $email)->row();
			if (empty($user))
			{
				throw new Exception("Пользователь с почтовым ящиком «{$email}» не зарегистрирован.");
			}
			if ((bool)$user['Active'] === false)
			{
				throw new Exception("Пользователь с почтовым ящиком «{$email}» заблокирован.");
			}

			/* Проверка пароля */
			_User::check_password($password);
			if (!password_verify($password, $user['Password']))
			{
				throw new Exception("Пароль указан неверно.");
			}
			

			/* Создать сессию */
			_User_Session::add("admin", "user", $user['ID']);
		}
	}
	
	/**
	 * Выход
	 * 
	 * @param string $in
	 */
	public static function logout($in)
	{
		_User_Session::delete($in);
	}

	/**
	 * Данные по пользователю
	 * 
	 * @param string $in
	 * @return array
	 */
	public static function data($in)
	{
		/* Данные по сессии */
		$session = _User_Session::get($in);

		/* Данные по пользователю */
		if ((int)$session['User_ID'] !== 0)
		{
			$query = 
<<<SQL
SELECT 
	"u"."ID", 
	"u"."Name", 
	"u"."Email",
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
			$user = G::db_core()->query($query, $session['User_ID'])->row();
		}
		/* Данные по root */
		else
		{
			$user['ID'] = "0";
			$user['Name'] = ROOT_NAME;
			$user['Email'] = ROOT_NAME;
			$user['Group_ID'] = "0";
			$user['Group_Name'] = "root";
		}

		$user['Session_ID'] = $session['ID'];
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
		/* Проверка */
		_User::check_password($old);
		_User::check_password($new);
		if ($old === $new)
		{
			throw new Exception("Старый и новый пароль совпадают.");
		}

		/* Если root */
		$session = _User_Session::get("admin");
		if ((int)$session['User_ID'] === 0)
		{
			throw new Exception("Чтобы сменить пароль у «" . ROOT_NAME_FULL . "», воспользуйтесь конфигурационным файлом.");
		}

		/* Проверить старый пароль */
		$query = 
<<<SQL
SELECT
	"ID",
	"Password"
FROM
	"user"
WHERE
	"ID" = $1
SQL;
		$user = G::db_core()->query($query, $session['User_ID'])->row();
		if (!password_verify($old, $user['Password']))
		{
			throw new Exception("Старый пароль указан неверно.");
		}

		/* Сменить пароль */
		_User::passwd($user['ID'], $new);
	}
	
	/**
	 * Назначить последнюю посещаемую страницу в админки или в конструкторе
	 * 
	 * @param string $in (constr|admin)
	 * @param string $hash
	 */
	public static function visit_last_set($in, $hash)
	{
		/* Данные по пользователю */
		$user = self::data($in);
		
		/* Проверка */
		if (!Type::check("string", ($hash)))
		{
			throw new Exception("Хэш задан неверно.");
		}
		
		/* Конструктор */
		if ($in === "constr")
		{
			P::set("constr_visit_last", $hash);
		}
		/* Админка, root */
		elseif ($in === "admin" and (int)$user['ID'] === 0)
		{
			P::set("admin_root_visit_last", $hash);
		}
		/* Админка, обычный пользователь */
		elseif ($in === "admin" and (int)$user['ID'] !== 0)
		{
			$data = 
			[
				"Visit_Last_Admin" => $hash
			];
			G::db_core()->update("user", $data, ["ID" => $user['ID']]);
		}
	}
	
	/**
	 * Получить последнюю посещаемую страницу в админки или в конструкторе
	 * 
	 * @param string $in (constr|admin)
	 */
	public static function visit_last_get($in)
	{
		/* Данные по пользователю */
		$user = self::data($in);
		
		/* Конструктор */
		if ($in === "constr")
		{
			return P::get("constr_visit_last");
		}
		/* Админка, root */
		elseif ($in === "admin" and (int)$user['ID'] === 0)
		{
			return P::get("admin_root_visit_last");
		}
		/* Админка, обычный пользователь */
		elseif ($in === "admin" and (int)$user['ID'] !== 0)
		{
			$query = 
<<<SQL
SELECT 
	"Visit_Last_Admin"
FROM
	"user"
WHERE
	"ID" = $1
SQL;
			return G::db_core()->query($query, $user['ID'])->single();
		}
	}
}
?>
