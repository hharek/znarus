<?php
/* Ключ от множества запросов */
if($_SERVER['REQUEST_METHOD'] === "GET")
{
	$_SESSION['feedback_secret_key'] = md5(mt_rand(0, 1000000) . microtime());
}

/* Данные по умолчанию */
$fdata = 
[
	"Name" => "",
	"Email" => "",
	"Telefon" => "",
	"Message" => ""
];

/* Отправка */
if($_SERVER['REQUEST_METHOD'] === "POST")
{
	try
	{
		/* Проверка ключа */
		if(md5($_SESSION['feedback_secret_key']) !== $_POST['secret_key'])
		{
			throw new Exception("Ошибка CSRF");
		}
		
		/* Проверка */
		$fdata['Name'] = trim($_POST['Name']);
		if(empty ($fdata['Name']))
		{Err::add("Вы не представились", "Name");}
		
		$fdata['Email'] = trim($_POST['Email']);
		if(!empty($fdata['Email']) and !preg_match("#^[a-z0-9_\-\.]+@[a-z0-9\-\.]+\.[a-z]{2,}$#isu", $fdata['Email']))
		{Err::add("«E-mail» задан неверно", "Email");}
		
		$fdata['Telefon'] = trim($_POST['Telefon']);
		
		if(empty($fdata['Email']) and empty($fdata['Telefon']))
		{Err::add("Укажите «E-mail» или «Телефон»", "Email");}
		
		$fdata['Message'] = trim($_POST['Message']);
		if(empty($fdata['Message']))
		{Err::add("Сообщение не задано", "Message");}
		
		Err::exception();

		/* Сформировать сообщение */
		ob_start();
		require Reg::path_app() . "/mod/feedback/other/message.html";
		$message = ob_get_contents();
		ob_end_clean();

		/* Отправка по почте */
		require_once Reg::path_app() . "/lib/phpmailer/PHPMailerAutoload.php";
		$email = explode(",", P::get("feedback", "email"));
		foreach ($email as $val)
		{
			$phpmailer = new PHPMailer();

			/* From */
			$phpmailer->From = "info@example.com";
			$phpmailer->FromName = P::get("feedback", "from_name");

			/* Address */
			$phpmailer->AddAddress(trim($val));

			/* Message */
			$phpmailer->IsHTML(true);
			$phpmailer->CharSet	= "UTF-8";
			$phpmailer->Subject = P::get("feedback", "subject");
			$phpmailer->Body = $message;

			/* Отправка */
			$phpmailer->Send();
		}
		
		/* Перенаправить */
		header("Location: /обратная-связь/сообщение-отправлено");
		
	}
	catch (Exception $e)
	{
//		echo $e->getMessage();
	}
}

/* Заголовок */
Reg::title("Связаться с нами");
Reg::meta_title("Связаться с нами");
Reg::meta_description("Уважаемые посетители, здесь вы можете связаться с нами. Для этого просто заполните соответствующие поля, введите сообщение и нажмите на кнопку отправить.");
Reg::meta_keywords("обратная связь, задать вопрос, связаться с нами, написать нам, пожаловаться, форма обратной связи");

Reg::path
([
	"Связаться с нами [/обратная-связь]"
]);
?>