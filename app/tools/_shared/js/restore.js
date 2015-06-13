/* -------------------- Авторизация --------------------*/
$(function()
{
	/* Повесить submit на форму */
	$("form").find("._button").not("._login").click(function()
	{
		$(this).submit();
	});

	/* Отправка файла */
	$("form").submit(function()
	{
		/* Отправка */
		$.ajax
		({
			url: "restore",
			type: "POST",
			data: new FormData($(this)[0]),
			dataType: "json",
			processData: false,
			contentType: false,
			cache: false,
			beforeSend: function()
			{
				$("#_overlay").show();
				$("#_loader").show();
			},
			success: function(data, textStatus, jqXHR)
			{
				if ($("form input[name=Type]").val() === "send" && data.error === undefined)
				{
					$("._email").remove();
					$("._button").remove();
					$("._text").text("На почтовый ящик «" + data.email +  "», отправлено письмо с инструкцией по восстановлению пароля.");
				}
				else if ($("form input[name=Type]").val() === "passwd" && data.error === undefined)
				{
					$("._password").remove();
					$("._button").hide();
					$("._text").text("Новый пароль установлен.");
					$("._login").css("display", "block");
				}
				else if (data.error !== undefined)
				{
					_alert(data.error);
				}
			},
			complete: function(jqXHR, textStatus)
			{
				$("#_loader").hide();
				$("#_overlay").hide();
			},
			error: function(jqXHR, textStatus, errorThrown)
			{
				alert(textStatus + ": " + errorThrown);
			}
		});
		
		return false;
	});
});