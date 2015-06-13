<?php
/**
 * Группы пользователей
 */
class _User
{
	/**
	 * Проверка по ID
	 * 
	 * @param int $id
	 */
	public static function is($id)
	{
		if (!Chf::uint($id))
		{
			throw new Exception("Номер у пользователя задан неверно. " . Chf::error());
		}

		$query = 
<<<SQL
SELECT 
	true
FROM 
	"user"
WHERE 
	"ID" = $1
SQL;
		$rec = G::db_core()->query($query, $id)->single();
		if ($rec === null)
		{
			throw new Exception("Пользователь с номером «{$id}» не существует.");
		}
	}
	
	/**
	 * Добавить
	 * 
	 * @param string $name
	 * @param string $email
	 * @param string $password
	 * @param int $group_id
	 * @param bool $active
	 * @return array
	 */
	public static function add($name, $email, $password, $group_id, $active)
	{
		/* Проверка */
		_User_Group::is($group_id);
		self::_check($name, $email, $active);
		
		try
		{
			self::check_password($password);
		}
		catch (Exception $e)
		{
			Err::add($e->getMessage(), "Password");
			Err::exception();
		}
		
		/* Соль */
		$salt = self::password_salt_random();

		/* Уникальность */
		self::_unique($name, $email);

		/* SQL - добавить */
		$data = 
		[
			"Name" => $name,
			"Email" => $email,
			"Group_ID" => $group_id,
			"Active" => (int)$active,
			"Salt" => $salt
		];
		$id = G::db_core()->insert("user", $data, "ID");
		
		/* SQL - назначить пароль */
		$data = 
		[
			"Password" => self::password_hash($password, $salt)
		];
		G::db_core()->update("user", $data, ["ID" => $id]);

		/* Данные добавленного */
		return self::get($id);
	}

	/**
	 * Редактировать
	 * 
	 * @param int $id
	 * @param string $name
	 * @param string $email
	 * @param int $group_id
	 * @param bool $active
	 * @return array
	 */
	public static function edit($id, $name, $email, $group_id, $active)
	{
		/* Проверка */
		self::is($id);
		_User_Group::is($group_id);
		self::_check($name, $email, $active);
		
		/* Уникальность */
		self::_unique($name, $email, $id);

		/* SQL */
		$data = 
		[
			"Name" => $name,
			"Email" => $email,
			"Group_ID" => $group_id,
			"Active" => (int)$active
		];
		G::db_core()->update("user", $data, ["ID" => $id]);

		/* Данные изменённого */
		return self::get($id);
	}

	/**
	 * Удалить
	 * 
	 * @param int $id
	 * @return array
	 */
	public static function delete($id)
	{
		/* Проверка */
		$old = self::get($id);

		/* SQL */
		G::db_core()->delete("user", ["ID" => $id]);

		/* Данные удалённого */
		return $old;
	}

	/**
	 * Получить хэш пароля
	 * 
	 * @param string $password
	 * @param string salt
	 * @return string
	 */
	public static function password_hash($password, $salt)
	{
		/* Соль */
		$salt = $salt . SALT;										/* Соль соединяем с общей солью, чтобы у пользователей с одинаковыми паролями были разные хэши */
		$salt = md5($salt);												/* Оставляем только символы a-z 0-9 */
		$salt = substr($salt, 0, 22);									/* В bcrypt используются только первые 22 символа */
		
		/* Опции хэша */
		$options = 
		[
			"cost" => PASSWORD_BCRYPT_COST,						/* Цена хэша bcrypt */
			"salt" => $salt
		];
		
		/* Получить хэш */
		$hash = password_hash($password, PASSWORD_BCRYPT, $options);	/* Получаем хэш bcrypt */
		$hash = md5($hash);												/* Делаем в 32 символа и прячим соль */

		return $hash;
	}

	/**
	 * Назначить пароль
	 * 
	 * @param int $id
	 * @param string $password
	 */
	public static function passwd($id, $password)
	{
		/* Проверка */
		self::is($id);
		self::check_password($password);
		
		/* Соль */
		$salt = self::password_salt_random();
		
		/* SQL */
		$data = 
		[
			"Password" => self::password_hash($password, $salt),
			"Salt" => $salt,
			"Password_Change_Date" => "now()",
			"Password_Change_Code" => null
		];
		G::db_core()->update("user", $data, ["ID" => $id]);
	}

	/**
	 * Выборка строки по ID
	 * 
	 * @param int $id
	 * @return array
	 */
	public static function get($id)
	{
		self::is($id);

		$query = 
<<<SQL
SELECT
	"ID",
	"Name",
	"Email",
	"Group_ID",
	"Active"::int,
	"Password_Change_Date"
FROM 
	"user"
WHERE 
	"ID" = $1
SQL;
		return G::db_core()->query($query, $id)->row();
	}

	/**
	 * Выборка всех по группе
	 * 
	 * @param int $group_id
	 * @return array
	 */
	public static function get_by_group($group_id)
	{
		_User_Group::is($group_id);
		
		$query = 
<<<SQL
SELECT
	"ID",
	"Name",
	"Email",
	"Group_ID",
	"Active"::int,
	"Password_Change_Date"
FROM 
	"user"
WHERE 
	"Group_ID" = $1
ORDER BY
	"Email" ASC
SQL;

		return G::db_core()->query($query, $group_id)->assoc();
	}

	/**
	 * Выборка всех пользователей
	 * 
	 * @return array
	 */
	public static function get_all()
	{
		$query = 
<<<SQL
SELECT 
	"ID", 
	"Name",
	"Email", 
	"Group_ID",
	"Active"::int,
	"Password_Change_Date"
FROM 
	"user"
ORDER BY 
	"Email" ASC
SQL;
		return G::db_core()->query($query)->assoc();
	}

	/**
	 * Отправить код на изменения пароля
	 * 
	 * @param string $email
	 */
	public static function password_change_code_send($email)
	{
		/* Проверка */
		$email = trim((string) $email);
		$email = strtolower($email);
		if ($email === "")
		{
			throw new Exception("Почтовый ящик не указан. ");
		}
		if (!Chf::email($email))
		{
			throw new Exception("Почтовый ящик задан неверно. ");
		}
		
		/* Поиск почтового ящика */
		$query = 
<<<SQL
SELECT
	"ID",
	"Email",
	"Active"::int
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
		
		/* Создать код */
		$password_change_code = md5(microtime() . mt_rand(1, 100000));
		$data =
		[
			"Password_Change_Code" => $password_change_code
		];
		G::db_core()->update("user", $data, ["ID" => $user['ID']]);
		$password_change_code_hash = md5(SALT . $password_change_code);
		
		/* Отправить сообщение */
		ob_start();
		require DIR_APP . "/smod/_user/html/password_change_code.html";
		$message = ob_get_contents();
		ob_end_clean();
		
		_Sender::send($email, "Восстановление пароля на сайте «" . DOMAIN . "»", $message);
	}
	
	/**
	 * Получить ID пользователя по коду
	 * 
	 * @param string $code
	 * @return bool
	 */
	public static function password_change_code_user_id($code)
	{
		/* Проверка */
		$code = trim((string)$code);
		if ($code === "")
		{
			return;
		}
		
		if (!Chf::identified($code))
		{
			return;
		}
		
		/* Поиск кода */
		$query = 
<<<SQL
SELECT 
	"ID",
	"Password_Change_Code"
FROM
	"user"
WHERE
	"Password_Change_Code" IS NOT NULL AND
	"Active" = true
SQL;
		$user = G::db_core()->query($query)->assoc();
		$password_isset = false; 
		foreach ($user as $val)
		{
			if (md5(SALT . $val['Password_Change_Code']) === $code)
			{
				return $val['ID'];
			}
		}
		if ($password_isset === false)
		{
			return;
		}
	}

	/**
	 * Сменить пароль по коду
	 * 
	 * @param string $code
	 * @param string $password
	 */
	public static function password_change_code($code, $password)
	{
		/* Проверка кода */
		$user_id = self::password_change_code_user_id($code);
		if ($user_id === null)
		{
			throw new Exception("Код задан неверно.");
		}
		
		/* Сменить пароль */
		self::passwd($user_id, $password);
	}

	/**
	 * Проверка пароля
	 * 
	 * @param string $password
	 */
	public static function check_password(&$password)
	{
		$password = trim((string)$password);
		
		if ($password === "")
		{
			throw new Exception("Не задан пароль.");
		}
		
		if (!Chf::string($password))
		{
			throw new Exception("Пароль задан неверно. " . Chf::error());
		}
		
		if (mb_strlen($password) < PASSWORD_LENGTH_MIN)
		{
			throw new Exception("Пароль не должен быть меньше " . PASSWORD_LENGTH_MIN . " символов.");
		}
		
		if (mb_strlen($password) > PASSWORD_LENGTH_MAX)
		{
			throw new Exception("Пароль не должен быть больше " . PASSWORD_LENGTH_MAX . " символов.");
		}
	}
	
	/**
	 * Получить соль для пароля пользователя
	 * 
	 * @return string
	 */
	public static function password_salt_random()
	{
		$str = md5(microtime(true) . mt_rand(0, 100000));
		$str = substr($str, 0, 4);
		return $str;
	}
	
	/**
	 * Создать открытый и закрытый ключ для авторизации через jsencrypt
	 */
	public static function jsencrypt_key_create()
	{
		/* Конфигурация для закрытого ключа */
		$config = 
		[
			"digest_alg" => "sha256",
			"private_key_bits" => 1024,
			"private_key_type" => OPENSSL_KEYTYPE_RSA
		];
		
		/* Создаём ресурс закрытого ключа */
		$res = openssl_pkey_new($config);
		
		/* Создать закрытый ключ */
		$private_key = "";
		openssl_pkey_export($res, $private_key);
		G::file_app()->put(JSENCRYPT_PRIVATE_KEY, $private_key);
		
		/* Создать открытый ключ на основе закрытого ключа */
		$public_key = openssl_pkey_get_details($res)['key'];
		G::file_app()->put(JSENCRYPT_PUBLIC_KEY, $public_key);
	}
	
	/**
	 * Проверка полей
	 * 
	 * @param string $name
	 * @param string $email
	 * @param bool $active
	 */
	private static function _check($name, &$email, &$active)
	{
		Err::check_field($name, "string", false, "Name", "Наименование");
		
		Err::check_field($email, "email", false, "Email", "E-mail");
		$email = strtolower($email);

		Err::check_field($active, "bool", false, "Active", "Активен");
		$active = (bool)$active;
		
		Err::exception();
	}
	
	/**
	 * Уникальность
	 * 
	 * @param string $name
	 * @param string $email
	 * @param int $id
	 */
	private static function _unique($name, $email, $id = null)
	{
		$query = 
<<<SQL
SELECT 
	true
FROM 
	"user"
WHERE 
	"Name" = $1 AND
	"ID" != $2
SQL;
		$rec = G::db_core()->query($query, [$name, (int)$id])->single();
		if ($rec !== null)
		{
			Err::add("Пользователь с полем «Наименование» : «{$name}» уже существует.", "Name");
		}

		$query = 
<<<SQL
SELECT 
	true
FROM 
	"user"
WHERE 
	"Email" = $1 AND
	"ID" != $2
SQL;
		$rec = G::db_core()->query($query, [$email, (int)$id])->single();
		if ($rec !== null)
		{
			Err::add("Пользователь с полем «E-mail» : «{$email}» уже существует.", "Email");
		}
		
		Err::exception();
	}
}
?>